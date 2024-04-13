<template>
	<ul class="c-pagination">
		<!-- 前のページに戻るボタン -->
		<li class="c-pagination__item">
			<a class="c-pagination__link" :class="{ notAllowed: current_page === 1 }" 
			@click="prevPage(current_page)">&lsaquo;前へ</a>
		</li>
		<!-- 各ページのボタン -->
		<li v-for="page in PageRange" :key="page" class="c-pagination__item">
			<a class="c-pagination__link" @click="changePage(page)" 
			:class="{ isActive: (isCurrent(page)), notAllowed: (isCurrent(page)) }">{{ page }}</a>
		</li>
		<!-- 次のページに進むボタン -->
		<li class="c-pagination__item">
			<a class="c-pagination__link" :class="{ notAllowed: current_page === last_page }" 
			@click="nextPage(current_page)">次へ&rsaquo;</a>
		</li>
	</ul>
</template>

<script>
import SearchJobMixin from '../mixin/SearchJobMixin.vue';

export default {
	mixins: [ SearchJobMixin ],

	methods:{
		// 前のページに戻るボタン
		prevPage(page) {
			if (page !== 1) {
				this.$store.commit('Pagination/decrementPage');
				this.changeUrl();
			}
		},
		// 次のページに進むボタン
		nextPage(page) {
			if (page !== this.last_page) {
				this.$store.commit('Pagination/incrementPage');
				this.changeUrl();
			}
		},

		// ページネーションに表示するページ番号を元に、配列を作成
		calRange(start, end) {
			const range = [];
			for (let i = start; i <= end; i++) {
				range.push(i);
			}
			return range;
		},
		
		// ページネーションの現在のページは色を変える
		isCurrent(page) {
			return page === this.current_page;
		},
	},

	computed: {
		// ページネーションに表示するページ番号を計算
		PageRange() {
			// ページネーションの現在ページは、可能な限り中央に配置する
			let start = '';
			let end = '';
			// ページネーション数より最終ページ数が少ない場合、１〜最終ページまでページネーションに表示
			if (this.last_page <= this.page_range) {
				start = 1;
				end = this.last_page;
			// 例）現在のページ数が３以下、ページネーション数が５の場合。ページネーションを１〜５で固定
			} else if (this.current_page <= this.page_range - Math.floor(this.page_range / 2)) {
				start = 1;
				end = this.page_range;
			// 例）最終ページ数が２０、ページネーション数が５の場合。１８〜２０ページはページネーションを１６〜２０で固定
			} else if (this.current_page > this.last_page - Math.floor(this.page_range / 2)) {
				start = this.last_page - this.page_range + 1;
				end = this.last_page;
			// その他の場合、現在のページ数の左右にページネーション数の半分（少数は切り捨て）を表示
			} else {
				start = this.current_page - Math.floor(this.page_range / 2);
				end = this.current_page + Math.floor(this.page_range / 2);
			}
			return this.calRange(start, end);
		},
	}
}
</script>

<style>
.isActive {
	background: #00c1ff;
	color: #fff;
}
.notAllowed{
	cursor: not-allowed !important;
}
</style>
