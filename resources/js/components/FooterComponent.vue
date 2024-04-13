<template>
	<!-- <footer class="l-footer" ref="footer" :style="styles"> -->
	<footer class="l-footer" ref="footer">
		<div class="c-inner--l l-footer__container">
			<ul class="l-footer__list">
				<li class="l-footer__listItem">
					<router-link :to="`/job20/rule`" class="l-footer__link">ご利用規約</router-link>
				</li>
				<li class="l-footer__listItem">
					<router-link :to="`/job20/privacy`" class="l-footer__link">プライバシーポリシー</router-link>
				</li>
				<li class="l-footer__listItem">
					<router-link :to="`/job20/contact`" class="l-footer__link">お問い合わせ</router-link>
				</li>
			</ul>
			<span class="l-footer__copy">
				Copyright <router-link :to="`/job20`">ジョブインフォ20</router-link>. All Rights Reserved.
			</span>
		</div>
	</footer>
</template>

<script>
export default {
	data(){
		return {
		}
	},
	methods: {
		// getScroll(){
		// console.log('===================== ');
		// console.log('window.innerHeight: ' + window.innerHeight);
		// console.log('offsetTop: ' + this.$refs.footer.offsetTop);
		// console.log('offsetHeight: ' + this.$refs.footer.offsetHeight);
		// console.log('getBoundingClientRect().top: ' + this.$refs.footer.getBoundingClientRect().top);
		// console.log('window.scrollY: ' + window.scrollY);
		// console.log('getBoundingClientRect().top + window.scrollY: ' + this.$refs.footer.getBoundingClientRect().top + window.scrollY);
		// }
		// 参考
		// https://ja.javascript.info/size-and-scroll
	},
	computed: {
		// styles(){
		// console.log('styles');

		// フッターを最下部に固定
		// 	if( window.innerHeight > this.$refs.footer.offset().top + this.$refs.footer.outerHeight() ){
		// 		return  {
		// 			position: 'fixed',
		// 			top: (window.innerHeight - this.$refs.footer.outerHeight()) + 'px' ,
		// 		}
		// 	}
		// }
	},

	mounted(){
		// console.log('mounted');
		// this.getScroll();
		// // スクロールイベント登録
		// window.addEventListener("scroll", this.getScroll);
	},

	updated(){
		// console.log('updated');
		// this.getScroll();
	},

	beforeDestroy: function () {
		// // スクロールイベント削除
		// window.removeEventListener("scroll", this.getScroll);
	}
}
</script>
