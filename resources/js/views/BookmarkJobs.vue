<template>
	<div>
		<!-- 求人応募モーダル -->
		<transition name="modal">
			<Modal v-show="isShowApply">
				<template slot="body">
					<div class="c-modal__body">
						<p class="c-modal__text">求人に応募します。よろしいですか？</p>
						<div class="c-modal__buttonArea" >
							<button class="c-modal__button" @click="applyJob(applyID), closeApplyModal()">はい</button>
							<button class="c-modal__button--no" @click.prevent="closeApplyModal()">いいえ</button>
						</div>
					</div>
				</template>
			</Modal>
		</transition>

		<!-- 気になる求人削除モーダル -->
		<transition name="modal">
			<Modal v-show="isShowDereteBookmarkJobsModal">
				<template slot="body">
					<div class="c-modal__body">
						<p class="c-modal__text">この求人を気になる求人リストから削除します。<br>よろしいですか？</p>
						<div class="c-modal__buttonArea" >
							<button class="c-modal__button" @click.prevent="removeBookmarkJob( modalBookmarkJobsNum(), modalBookmarkJobsID() ), close()">OK</button>
							<button class="c-modal__button--no" @click="close()">キャンセル</button>
						</div>
					</div>
				</template>
			</Modal>
		</transition>

		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="title"></BreadCrumb>

		<div class="p-bookmarkJobs c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- WEB履歴書必須のカード -->
				<RequireWebResumeCard v-if="isShowCard && isDataAcquired"></RequireWebResumeCard>
				<!-- 公開時の変更 -->
				<!-- <RequireWebResumeCard v-if="this.jobs.length > 0 && isDataAcquired"></RequireWebResumeCard> -->

				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-star"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<section class="c-section">
					<div class="c-section__container u-radius5">
						
						<!-- 気になる求人がある場合 -->
						<template v-if="jobs.length > 0">

							<div class="c-searchNaviArea p-bookmarkJobs__searchNaviArea">
								<!-- 気になる求人の件数表示 -->
								<div class="c-searchNaviArea__counter p-bookmarkJobs__counter">
									全<span class="c-searchNaviArea__countNum p-bookmarkJobs__totalNum">{{ jobs.length }}</span>件を表示中
								</div>
								<!-- 並び替え -->
								<div class="p-bookmarkJobs__sortArea">
									<span class="c-searchNaviArea__sortText u-color-text">並び順</span>
									<div class="c-selectBox p-bookmarkJobs__selectBox">
										<select v-model="sort" class="c-selectBox__select--sort" name="sort" @change="sortBy(sort)">
											<option value="1">応募締切順</option>
											<option value="2">リスト追加順</option>
										</select>
									</div>
								</div>
							</div>

							<!-- 気になる求人の件数リスト -->
							<transition-group name="fade" tag="ul" class="p-bookmarkJobs__list">
								<li v-for="(job, index) in sortedJobs" :key="job.id" class="p-bookmarkJobs__item">
									<!-- スマホ画面の削除アイコン -->
									<button class="p-bookmarkJobs__deleteIcon" v-show="isWidthS" @click.prevent="open(index, job.id)">
										<i class="fas fa-times"></i>
									</button>
									<div class="p-bookmarkJobs__unit">
										<div class="p-bookmarkJobs__subUnit">
											<div class="p-bookmarkJobs__photo">
												<router-link :to="`/job20/job/${job.id}`">
													<img :src="'https://d38rk2cibjrg07.cloudfront.net/job_change_20/' + job.photo1" alt="">
												</router-link>
											</div>
											<div class="p-bookmarkJobs__nameSet">
												<router-link class="p-bookmarkJobs__jobName" :to="`/job20/job/${job.id}`">{{ job.job_name }}</router-link>
												<div class="p-bookmarkJobs__company">{{ job.company_name }}</div>
												<div class="p-bookmarkJobs__catch">{{ job.job_catch }}</div>
											</div>
										</div>
										<!-- ボタンエリア -->
										<div class="p-bookmarkJobs__buttonSet" :class="{'p-bookmarkJobs__buttonSet--changeFlex': change_flex(job.deadline) }">
											<div class="p-bookmarkJobs__buttonBase" :class="[ {'u-bgColor-Sub': change_bg(job.deadline) }, {'p-bookmarkJobs__buttonBase--changeDisplay': change_display(job.deadline) } ]">
												<div v-if="diffDay(job.deadline) < 15" class="p-bookmarkJobs__count" :class="{noAfter: diffDay(job.deadline) < 0 || !hasWebResume}">
												<!-- 公開時の変更 -->
												<!-- <div v-if="diffDay(job.deadline) < 15" class="p-bookmarkJobs__count" :class="{noAfter: diffDay(job.deadline) < 0}"> -->
													<template  v-if="diffDay(job.deadline) < 15 && diffDay(job.deadline) > 0">
														<div class="p-bookmarkJobs__countItem">掲載終了まで</div>
														<div class="p-bookmarkJobs__countDate">
															<span class="p-bookmarkJobs__countNum">{{ diffDay(job.deadline) }}</span>日
														</div>
													</template>
													<template v-else-if="diffDay(job.deadline) === 0">
														<div class="p-bookmarkJobs__countItem">応募締切</div>
														<div class="p-bookmarkJobs__countDate">
															<span class="p-bookmarkJobs__countNum u-mr0">本日!</span>
														</div>
													</template>
													<template v-else-if="diffDay(job.deadline) < 0">
														<div class="p-bookmarkJobs__countItem">募集終了</div>
													</template>
												</div>
												<div v-if="hasWebResume" class="p-bookmarkJobs__buttonArea"">
												<!-- 公開時の変更 -->
												<!-- <div class="p-bookmarkJobs__buttonArea""> -->
													<!-- 応募ボタン-->
													<button v-if="diffDay(job.deadline) >= 0" @click.prevent="openApplyModal(index, job.id)" 
														class="c-btn--apply p-bookmarkJobs__btn" :disabled="isClicked">
														<i class="fa-regular fa-circle-check"></i>応募<span class="u-spHidden">する</span>
													</button>
													<!-- 気になるボタン-->
													<bookmark-btn-component v-if="diffDay(job.deadline) < 0 && isDataAcquired" :id="job.id" class="c-btn--bookmark p-bookmarkJobs__btn"></bookmark-btn-component>
												</div>
											</div>
										</div>
									</div>
									<div v-if="!isWidthS" class="p-bookmarkJobs__termArea">
										<div class="p-bookmarkJobs__termUnit">
											<div class="p-bookmarkJobs__datearea">
												<span class="p-bookmarkJobs__deadline">応募締切 : <span class="u-spShow"><br></span>{{ dateFormat(job.deadline) }}</span>
												<span class="u-tabHidden"> / </span>
												<span class="u-tabShow"><br></span>
												<span class="p-bookmarkJobs__keep">リスト追加日 : <span class="u-spShow"><br></span>{{ dateFormat(job.pivot.created_at) }}</span>
											</div>
											<div class="p-bookmarkJobs__delete" @click.prevent="openAlert(job.id)">
												<a href="#"><i class="fa-solid fa-trash-can-arrow-up"></i>削除</a>
											</div>
										</div>

										<transition @before-enter="beforeEnter" @enter="enter" @before-leave="beforeLeave" @leave="leave">
											<div v-if="isOpen(job.id)" class="p-bookmarkJobs__deleteAlert">
												<p class="p-bookmarkJobs__alertText">この求人を気になる求人リストから削除します。よろしいですか？</p>
												<a class="p-bookmarkJobs__okButton" @click.prevent="removeBookmarkJob(index, job.id)" :disabled="isClicked">OK</a>
												<a class="p-bookmarkJobs__noButton" @click="closeAlert(job.id)">キャンセル</a>
											</div>
										</transition>
									</div>
								</li>
							</transition-group>
						</template>

						<!-- 気になる求人がない場合 -->
						<template v-else>
							<div class="c-noData">※現在掲載中の「気になる求人」はありません。</div>
						</template>
					</div>
				</section>
			</div>
		</div>
	</div>
</template>

<script>
import ApplyJob from '../mixin/ApplyJob.vue';
import BookmarkButton from '../components/BookmarkButton.vue';
import BreadCrumb from '../components/BreadCrumb.vue';
import HandleResize from '../mixin/HandleResize.vue';
import JobInfoProcessing from '../mixin/JobInfoProcessing.vue';
import Loading from '../components/Loading.vue';
import Modal from '../components/Modal.vue';
import RequireWebResumeCard from '../components/RequireWebResumeCard.vue';

export default {
	components: {
		"bookmark-btn-component": BookmarkButton,
		"BreadCrumb": BreadCrumb,
		"HandleResize": HandleResize,
		"Loading": Loading,
		"Modal": Modal,
		"RequireWebResumeCard": RequireWebResumeCard,
	},

	mixins: [ ApplyJob, HandleResize, JobInfoProcessing ],

	data(){
		return {
			title: '気になる求人',
			jobs: [], // 表示する求人の配列
			activeAlertArray: [],
			sort: 1,
			order: '',
			hasWebResume: false, // WEB履歴書の全登録フラグ
			isDataAcquired: false, 
		}
	},

	methods:{
		// モーダル開閉
		open(propNum, propID) {
			this.$store.commit('Modal/open', { modalName : "BookmarkJobs", num: propNum, id: propID });
        },
		close() {
			this.$store.commit('Modal/close', { modalName : "BookmarkJobs" });
        },

		// モーダル情報を返却
		modalBookmarkJobsNum: function () {
			return this.$store.state.Modal.modal.BookmarkJobs.num;
		},
		modalBookmarkJobsID: function () {
			return this.$store.state.Modal.modal.BookmarkJobs.id;
		},

		// 求人削除アラート表示・非表示
		openAlert: function (num) {
			if(!this.activeAlertArray.includes(num)){
				this.activeAlertArray.push(num);
			}
		},
		closeAlert: function (num) {
			let newArray = this.activeAlertArray.filter(function(number) {
				return number !== num;
			});
			this.activeAlertArray = newArray;
		},
		isOpen: function (num) {
			return this.activeAlertArray.includes(num);
		},

		// 求人削除アラートのアニメーション
		beforeEnter: function (el) {
			el.style.height = '0';
        },
        enter: function (el) {
			el.style.height = el.scrollHeight + 'px';
        },
        beforeLeave: function (el) {
			el.style.height = el.scrollHeight + 'px';
        },
        leave: function (el) {
			el.style.height = '0';
        },

		// インデックス番号を返却
		jobIndex: function () {
			return this.$store.state.Modal.modal.apply.num;
		},

		// 並べ替え
		sortBy: function(num) {
			switch(num){
				case '1':
					this.order = 'deadline';
					break;
				case '2':
					this.order = 'keepDate';
					break;
			}
		},

		// 気になる求人の情報取得
		getBookmarkJobs: async function () {
			// axiosで使うthisを格納
			const self = this;

			await axios.post('/job20/web/bookmark_jobs', {
				appliedFlg: false, // ページ判定フラグ
			}).then(response => {
				if(response.data.successFlg){
					self.jobs = response.data.jobs;
					self.hasWebResume = response.data.hasWebResume;
					self.isDataAcquired = true;
					self.isLoading = false;
				}else{
					self.$router.push({ name: 'error' });	
				}
			}).catch(error => console.log(error) );
		},
	},

	computed: {
		// WEB履歴書必須カード表示の判定
		isShowCard(){
			return this.jobs.length > 0 && !this.hasWebResume;
		},
		
		// クラス名付与の判定
		change_bg: function() {
			return function(deadline) {
				return this.diffDay(deadline) < 0;
			}
		},
		change_display: function() {
			return function(deadline) {
				return this.diffDay(deadline) > 15;
			}
		},
		change_flex: function() {
			return function(deadline) {
				return this.diffDay(deadline) > 15;
			}
		},

		sortedJobs: function() {
			// sort()は元の配列の中身もソートされてしまうため、slice()で配列のコピーを作成してからソート
			let list = this.jobs.slice();

			list.sort((a, b) => {
				if(this.order === 'deadline'){
					return ( (a.deadline > b.deadline) ? 1 : -1 );
				}else if(this.order === 'keepDate'){
					return ( (a.pivot.created_at > b.pivot.created_at) ? 1 : -1 );
				}
			});

			// list.forEach(item => {
			// 	if(this.sort === 'deadline'){
			// 		console.log(item.deadline);
			// 	}else if(this.sort === 'keepDate'){
			// 		console.log(item.pivot.created_at);
			// 	}
			// });

			return list;
		},
    },

	created(){
		// 気になる求人一覧を取得
		this.getBookmarkJobs();
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 2);
	},

	mounted(){
	},

	updated(){
		// 子コンポーネントがマウントされた後の処理
		// this.$nextTick(()=>{
		// });
	},

	beforeDestroy() {
	},
}
</script>

<style>
/* 非表示までのアニメーション */
.fade-move{
	transition: all 0s;
}
.fade-leave-active{
	transition: all 1s;
}
.fade-leave-to{
	opacity: 0;
}

.noAfter{
	padding-left: 15px;
	padding-right: 15px;
}
.noAfter::after{
	content: none;
}
</style>