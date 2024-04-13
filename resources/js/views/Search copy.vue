<template>
	<div>
		<!-- フラッシュメッセージ -->
		<FlashMessage></FlashMessage>

		<!-- ロード中 -->
		<Loading v-if="isLoading"></Loading>

		<div class="p-search c-inner--l" :class="{showPage: !isLoading}">
			<!-- カルーセル -->
			<!-- v-showでdisplay:noneにすると、要素の大きさが取得できないので、opacity:0で見えなくしておく -->
			<Carousel v-if="isShowCarousel && isDataAcquired" :jobList="pickupJobs"></Carousel>

			<div v-if="total > 0" class="c-searchNaviArea">
				<!-- タブレットより大きい画面 -->
				<template v-if="isShowSidebar">
					<!-- ページネーションの件数表示 -->
					<div class="c-searchNaviArea__counter">
						<span>全<span class="c-searchNaviArea__countNum">{{ total }}</span>件</span>
						<span class="c-searchNaviArea__countData">({{ (current_page - 1) * job_range + 1 }}〜{{ (current_page - 1) * job_range + jobsNum }}件を表示)</span>
					</div>
					<!-- ページネーション -->
					<div class="c-searchNaviArea__pagination">
						<Pagination></Pagination>
					</div>
					<!-- 並び替え -->
					<div class="c-searchNaviArea__sort">
						<span class="c-searchNaviArea__sortText">並び順</span>
						<div class="c-selectBox p-search__selectBox">
							<select v-model="sort" class="c-selectBox__select--sort" name="sort" @change="changePage(1)">
								<option v-for="item in sortData" :value="item.id" :key="item.id" v-html="item.name"></option>
							</select>
						</div>
					</div>
				</template>

				<!-- タブレットより小さい画面 -->
				<template v-else>
					<div class="c-searchNaviArea__searchUnit">
						<!-- ページネーションの件数表示 -->
						<div class="c-searchNaviArea__counter">
							全<span class="c-searchNaviArea__countNum">{{ total }}</span>件
							<span class="c-searchNaviArea__countData">({{ (current_page - 1) * job_range + 1 }} 〜 {{ (current_page - 1) * job_range + jobsNum }}件を表示)</span>
						</div>
						<!-- 検索ボタン -->
						<a class="c-searchBox" @click="reverseActiveSidebar" :style="justifyContent">
							<template v-if="searchRule">
								<span class="c-searchBox__ruleWrap">
									<span class="c-searchBox__rule">{{ searchRule }}</span>
								</span>
								<span class="c-searchBox__text"><i class="fa-solid fa-magnifying-glass"></i>条件変更</span>
							</template>
							<span v-else class="c-searchBox__text" :style="textAlign"><i class="fa-solid fa-magnifying-glass"></i>求人検索</span>
						</a>
						<!-- タブレットより小さい画面の検索パネル -->
						<div class="c-searchNaviArea__searchPanel" :class="{ active: isActiveSearchPanel, clip: isShowCommitmentModal() }">
							<SearchPanel></SearchPanel>
						</div>
					</div>
					<!-- 並び替え -->
					<ul class="c-searchNaviArea__sort">
						<li class="c-searchNaviArea__sortItem" 
							v-for="item in sortData" :value="item.id" :key="item.id" 
							:class="{selected: sort == item.id}" 
							@click="changeSortNum(item.id), changePage(1)" v-html="item.name">
						</li>
					</ul>
					<!-- ページネーション -->
					<div class="c-searchNaviArea__pagination">
						<Pagination></Pagination>
					</div>
				</template>
			</div>

			<div class="p-search__container">
				<!-- サイドバー -->
				<div v-if="isShowSidebar" class="p-search__sideBar">
					<SearchPanel></SearchPanel>
				</div>

				<!-- メインエリア -->
				<div class="p-search__main">					
					<!-- 求人リスト -->
					<div v-if="jobs.length > 0" class="">					
						<div v-for="(job, index) in jobs" :key="index" class="c-jobList">
							<div class="c-jobList__unit">
								<div class="c-jobList__jobNameArea">
									<router-link class="c-jobList__link" :to="`/job20/job/${job.id}`">
										<div class="c-jobData">
											<!-- 日付 -->
											<div class="c-jobData__date">
												<span>掲載期間 : <span>{{ dateFormat(job.created_at) }}</span></span>
												<span> ～ <span>{{ dateFormat(job.deadline) }}</span></span>
											</div>
											<!-- 求人名 -->
											<h2 class="c-jobData__jobName c-lineClamp--line3or4">{{ job.job_name }}</h2>
											<!-- 会社名 -->
											<div class="c-jobData__companyName c-lineClamp--line1or2">{{ job.company_name }}</div>
											<!-- バッジエリア -->
											<ul class="c-jobData__badgeArea p-search__badgeArea">
												<li class="c-jobData__badgeItem--employmentStatus">{{ job.employment_status_name }}</li>
												<li class="c-jobData__badgeItem--occupation">{{ job.occupation_name }}</li>
												<li class="c-jobData__badgeItem--industry">{{ job.industry_name }}</li>
												<li class="c-jobData__badgeItem--prefecture">{{ job.prefecture_name }}</li>
												<li v-for="commitment in job.commitments" v-show="!isWidthS && job.commitments" :key="commitment" class="c-jobData__badgeItem--commitment">{{ commitment }}</li>
											</ul>
										</div>
									</router-link>
								</div>

								<div class="c-jobList__base">
									<!-- 写真エリア -->
									<div class="c-jobList__photoArea">
										<router-link class="c-jobList__photoLink" :to="`/job20/job/${job.id}`">
											<img class="c-jobList__photo" :src = "'https://d38rk2cibjrg07.cloudfront.net/job_change_20/' + job.photo1">
										</router-link>
									</div>

									<!-- キャッチエリア -->
									<div class="c-catchArea">
										<div class="c-catchArea__title c-lineClamp--line2or3">{{ job.job_catch }}</div>
										<div class="c-catchArea__text c-lineClamp--line3or4">{{ job.job_summary }}</div>
									</div>

									<!-- データエリア -->
									<ul class="c-jobList__dataList">
										<li class="c-jobList__data">
											<span class="c-jobList__item">
												<span class="c-jobList__icon">仕事<br class="u-spShow">内容<i class="fa-solid fa-briefcase"></i></span>
											</span>
											<span class="c-jobList__text">
												<span class="c-lineClamp--line3or4">{{ job.job_description }}</span>
											</span>
										</li>
										<li class="c-jobList__data">
											<span class="c-jobList__item">
												<span class="c-jobList__icon">応募<br class="u-spShow">条件<i class="fa-solid fa-table-list"></i></span>
											</span>
											<span class="c-jobList__text">
												<span class="c-lineClamp--line3or4">{{ job.application_conditions }}</span>
											</span>
										</li>
										<li class="c-jobList__data">
											<span class="c-jobList__item">
												<span class="c-jobList__icon">年収<i class="fa-solid fa-yen-sign"></i></span>
											</span>
											<span class="c-jobList__text">
												<span class="c-lineClamp--line2or3">{{ priceFormat(job.annual_income) }}円〜</span>
											</span>
										</li>
										<li class="c-jobList__data">
											<span class="c-jobList__item">
												<span class="c-jobList__icon">勤務地<i class="fa-solid fa-location-dot"></i></span>
											</span>
											<span class="c-jobList__text">
												<span class="c-lineClamp--line2or3">{{ job.work_location }}</span>
											</span>
										</li>
									</ul>

									<!-- ボタンエリア -->
									<div class="c-jobList__btnArea">
										<!-- 未応募の求人で「気になるボタン」を表示 -->
										<bookmark-btn-component v-if="!job.appliedFlg && isDataAcquired" :id="job.id" class="c-jobList__btn"></bookmark-btn-component>
										<router-link class="c-btn c-jobList__btn--detail" :to="`/job20/job/${job.id}`">詳細へ</router-link>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- 求人リストここまで -->

					<!-- 検索条件に一致する求人がない場合 -->
					<div v-else class="c-searchNaviArea--zeroAnnounce">
						<p class="c-searchNaviArea__zeroAnnounceCopy">条件にあてはまる求人情報がありませんでした。</p>
						<p class="c-searchNaviArea__zeroAnnounceText">検索条件を広げてみて検索を行ってみてください。<br>新たに転職情報を発見できるチャンスです!!</p>
					
						<template v-if="isWidthS || isWidthM">
							<!-- 検索ボタン -->
							<a class="c-searchBox--s u-mt20" @click="reverseActiveSidebar">
								<span class="c-searchBox__text"><i class="fa-solid fa-magnifying-glass"></i>条件変更</span>
							</a>
							<!-- タブレットより小さい画面の検索パネル -->
							<div class="c-searchNaviArea__searchPanel" :class="{ active: isActiveSearchPanel, clip: isShowCommitmentModal() }">
								<SearchPanel></SearchPanel>
							</div>
						</template>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import BookmarkButton from '../components/BookmarkButton.vue';
import Carousel from '../components/Carousel.vue';
import Commitments from '../mixin/Commitments.vue';
import FlashMessage from '../components/FlashMessage.vue';
import JobInfoProcessing from '../mixin/JobInfoProcessing.vue';
import Loading from '../components/Loading.vue';
import Modal from '../components/Modal.vue';
import Pagination from '../components/Pagination.vue';
import SearchPanel from '../components/SearchPanel.vue';
import SearchJobMixin from '../mixin/SearchJobMixin.vue';

export default {
	components: {
		"bookmark-btn-component": BookmarkButton,
		"Carousel": Carousel,
		"FlashMessage": FlashMessage,
		"Loading": Loading,
		"Modal": Modal,
		"Pagination": Pagination,
		"SearchPanel": SearchPanel,
	},

	mixins: [ Commitments, JobInfoProcessing, SearchJobMixin ],

	data(){
		return {
			sortData: [
				{'id' : 1, 'name' : '新着順'},
				{'id' : 2, 'name' : '締切<span class="u-spHidden">が近い</span>順'},
				{'id' : 3, 'name' : '年収<span class="u-spHidden">が高い</span>順'},
			],
		}
	},

	methods: {
		// 画面幅を検知するイベント
		handleResize: function() {
			// resizeのたびにこいつが発火するので、ここでやりたいことをやる
			const width = window.innerWidth;

			// 検索パネルの表示切り替え
			if(width > 768){
				this.$store.commit('Pagination/setIsShowSidebar', true);
			}else{
				this.$store.commit('Pagination/setIsShowSidebar', false);
				this.$store.commit('Pagination/setIsActiveSearchPanel', false);
			}

			// 画面サイズ判定
			if(width < 414){
				this.$store.commit('Pagination/setIsWidthS', true);
				this.$store.commit('Pagination/setIsWidthM', false);
			}else if(width < 768 && width > 414){
				this.$store.commit('Pagination/setIsWidthS', false);
				this.$store.commit('Pagination/setIsWidthM', true);
			}else{
				this.$store.commit('Pagination/setIsWidthS', false);
				this.$store.commit('Pagination/setIsWidthM', false);
			}

			// 画面サイズがPCからタブレット以下に変更されたら、モーダル非表示
			if( (this.isWidthS || this.isWidthM) && this.$store.state.Modal.modal.commitment.isShow){
				this.$store.commit('Modal/close', { modalName : "commitment" });
			}
		},

		// 検索条件があれば検索、なければ全件表示
		selectShowJobs(){
			if(this.$store.state.Pagination.industry != 0 || this.$store.state.Pagination.occupation != 0 || this.$store.state.Pagination.employment_status != 0 || 
			   this.$store.state.Pagination.prefecture != 0 || this.$store.state.Pagination.annual_income != 0  || this.$store.state.Pagination.sort != 1 ||
			   this.$store.state.Pagination.commitments.length > 0 || this.$store.state.Pagination.keyword !== null ) {
			   this.$store.dispatch('Pagination/searchJobs');
			}else{
				this.$store.dispatch('Pagination/getJobs');
			}
		},

		// こだわり条件モーダルの表示フラグを返却
		isShowCommitmentModal(){
			return this.$store.state.Modal.modal.commitment.isShow;
		},

		//ブラウザバック、進むを検知した時に発火
		handlePopstate() {
			// タブレットより小さい画面で検索パネルが表示されていたら、非表示にする
			if (this.$store.state.Pagination.isActiveSearchPanel === true) {
				this.$store.commit('Pagination/setIsActiveSearchPanel', false);
			}

			// urlに検索パラメータがあればデータ更新、なければ初期化
			// ページ番号
			if(this.$route.query.page){
				this.$store.commit('Pagination/setCurrentPage', Number(this.$route.query.page));
			}else{
				this.$store.commit('Pagination/setCurrentPage', 1);
			}
			// 業種
			if (this.$route.query.industry) {
				this.$store.commit('Pagination/setIndustry', Number(this.$route.query.industry));
			}else{
				this.$store.commit('Pagination/setIndustry', 0);
			}
			// 職種
			if (this.$route.query.occupation) {
				this.$store.commit('Pagination/setOccupation', Number(this.$route.query.occupation));
			}else{
				this.$store.commit('Pagination/setOccupation', 0);
			}
			// 雇用形態
			if (this.$route.query.employment_status) {
				this.$store.commit('Pagination/setEmploymentStatus', Number(this.$route.query.employment_status));
			}else{
				this.$store.commit('Pagination/setEmploymentStatus', 0);
			}
			// 都道府県
			if (this.$route.query.prefecture) {
				this.$store.commit('Pagination/setPrefecture', Number(this.$route.query.prefecture));
			}else{
				this.$store.commit('Pagination/setPrefecture', 0);
			}
			// 年収
			if (this.$route.query.annual_income) {
				this.$store.commit('Pagination/setAnnualIncome', Number(this.$route.query.annual_income));
			}else{
				this.$store.commit('Pagination/setAnnualIncome', 0);
			}
			// 並び順
			if (this.$route.query.sort) {
				this.$store.commit('Pagination/setSort', Number(this.$route.query.sort));
			}else{
				this.$store.commit('Pagination/setSort', 1);
			}
			// キーワード
			if (this.$route.query.keyword) {
				this.$store.commit('Pagination/setKeyword', this.$route.query.keyword);
			}else{
				this.$store.commit('Pagination/setKeyword', null);
			}

			// こだわり条件
			if (this.$route.query.commitments) {
				// 複数のこだわり条件が「_」でつながった文字列のため、「_」で区切って配列に変換
				const str = this.$route.query.commitments;
				const arr = str.split('_');
				this.$store.commit('Pagination/setCommitmentsAll', arr);

				// チェックつけたアイテムが所属するカテゴリ、チェックマークをつけるアイテムの配列を空にする
				// これがないと次の処理がうまくいかない
				this.$store.commit('Pagination/setCategories', []);
				this.$store.commit('Pagination/setActiveCheckArray', []);
				this.$store.commit('Pagination/setCategoryNum', 1);

				// 配列をループ処理
				arr.forEach(item => {
					// チェックつけたアイテムが所属するカテゴリを確認
					this.checkItemsCategory('typeCommitment', 0, item);
					// チェックマークをつけるアイテムの配列を変更
					this.changeActiveCheckArray('typeCommitment', item);
				});
			}else{
				this.$store.commit('Pagination/setCommitmentsAll', []);
			}

			// カルーセル表示判定
			this.showCarousel();

			// this.selectShowJobs();
		},
	},
	
	computed: {
		// VueXのデータ取得
		// 検索パネルの表示フラグ
		isActiveSearchPanel: {
			get() {
				return this.$store.state.Pagination.isActiveSearchPanel;
			},
		},
		// 画面サイズ
		isWidthS: {
			get() {
				return this.$store.state.Pagination.isWidthS;
			},
		},
		isWidthM: {
			get() {
				return this.$store.state.Pagination.isWidthM;
			},
		},
		jobs: function () {
			return this.$store.state.Pagination.jobs;
		},	
		pickupJobs: function () {
			return this.$store.state.Pagination.pickupJobs;
		},
		job_range: function () {
			return this.$store.state.Pagination.job_range;
		},
		total: function () {
			return this.$store.state.Pagination.total;
		},
		jobsNum: function () {
			return this.$store.state.Pagination.jobsNum;
		},
		isLoading: function () {
			return this.$store.state.Pagination.isLoading;
		},
		isShowCarousel: function () {
			return this.$store.state.Pagination.isShowCarousel;
		},
		// 非同期通信のデータ取得判定
		isDataAcquired: function () {
			return this.$store.state.Pagination.isDataAcquired;
		},

		// スタイル追加
		justifyContent(){
			if(!this.searchRule){
				return { "justify-content": "center" } ;
			}
		},
		textAlign(){
			if(!this.searchRule){
				return { "text-align": "center" } ;
			}
		},
	},
	
	created(){
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 1);

		// 検索ページではサイトタイトルのクリック無効
		this.$store.commit('Header/setIsSearch', true);

		this.changeUrl();
		// リロード後、URLにパラメータがあれば検索、なければ全レコードを表示
		this.selectShowJobs();

		// 画面幅を取得するイベントを設定
		this.handleResize();
		window.addEventListener('resize', this.handleResize);
		//ブラウザバックも任意の検索条件を適用するメソッド発火
		window.addEventListener("popstate", this.handlePopstate)
	},

	mounted(){
	},

	updated(){
		// 子コンポーネントがマウントされた後の処理
		this.$nextTick(()=>{
			if(this.isLoading){
				setTimeout(() => {
					this.$store.commit('Pagination/setIsLoading', false);
				}, 300);
			}
		});
	},

	// ページ遷移時の処理
	beforeRouteUpdate (to, from, next) {
		// console.log('beforeRouteUpdate');

		// 遷移先URLに検索パラメータがあれば、データ更新
		if(to.query.industry){
			this.$store.commit('Pagination/setIndustry', to.query.industry);
		}
		if(to.query.occupation){
			this.$store.commit('Pagination/setOccupation', to.query.occupation);
		}
		if(to.query.employment_status){
			this.$store.commit('Pagination/setEmploymentStatus', to.query.employment_status);
		}
		if(to.query.prefecture){
			this.$store.commit('Pagination/setPrefecture', to.query.prefecture);
		}
		if(to.query.annual_income){
			this.$store.commit('Pagination/setAnnualIncome', to.query.annual_income);
		}
		if(to.query.commitments) {
			// 複数のこだわり条件が「_」でつながった文字列のため、「_」で区切って配列に変換
			const str = to.query.commitments;
			const arr = str.split('_');
			this.$store.commit('Pagination/setCommitmentsAll', arr);
		}
		if(to.query.keyword){
			this.$store.commit('Pagination/setKeyword', to.query.keyword);
		}
		if(to.query.sort){
			this.$store.commit('Pagination/setSort', to.query.sort);
		}

		// 検索処理
		this.selectShowJobs();
        next();
    },

	beforeDestroy() {
		this.$store.commit('Pagination/setIsLoading', true);
		this.$store.commit('Header/setIsSearch', false);

		//EventListener削除
		window.removeEventListener('resize', this.handleResize)
		window.removeEventListener("popstate", this.handlePopstate);
	},
}
</script>

<style scoped>
.showPage{
	opacity: 1;
}
.clip{
	overflow: clip;
}
</style>
