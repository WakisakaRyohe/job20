<template>
	<div>
		<!-- 求人応募確認のモーダル -->
		<transition name="modal">
			<Modal v-show="isShowApply">
				<template slot="body">
					<div class="c-modal__body">
						<p class="c-modal__text">求人に応募します。よろしいですか？</p>
						<div class="c-modal__buttonArea" >
							<button class="c-modal__button" @click="applyJob(job.id), closeApplyModal()">はい</button>
							<button class="c-modal__button--no" @click.prevent="closeApplyModal()">いいえ</button>
						</div>
					</div>
				</template>
			</Modal>
		</transition>

		<!-- サムネイル画像拡大表示のモーダル -->
		<transition name="fade">
			<Modal v-show="isShowModal">
				<template slot="body">
						<div class="c-modal__lightBox">
							<img :src="src" alt="">
							<button class="c-modal__closeButton" @click="close">
								<i class="fas fa-times"></i>
							</button>
						</div>
				</template>
			</Modal>
		</transition>

		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="job.job_name"></BreadCrumb>

		<div class="p-jobDetail c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<template v-else>
				<!-- WEB履歴書必須のカード -->
				<RequireWebResumeCard v-if="isShowCard && isDataAcquired"></RequireWebResumeCard>
				<!-- 公開時の変更 -->
				<!-- <RequireWebResumeCard v-if="!this.isApplied && isDataAcquired"></RequireWebResumeCard> -->

				<div class="p-jobDetail__descArea">
					<div class="c-jobData">
						<!-- 日付 -->
						<div class="c-jobData__date p-jobDetail__date">
							<span>情報更新日:{{ dateFormat(job.updated_at) }} / </span>			
							<span>掲載終了予定日:{{ dateFormat(job.deadline) }}</span>
						</div>
						<!-- 求人名 -->
						<h2 class="c-jobData__jobName c-lineClamp--line3or4">{{ job.job_name }}</h2>
						<!-- 会社名 -->
						<div class="c-jobData__companyName c-lineClamp--line1or2">{{ company.company_name }}</div>
						<!-- バッジエリア -->
						<ul class="c-jobData__badgeArea">
							<li class="c-jobData__badgeItem--employmentStatus">{{ job.employment_status_name }}</li>
							<li class="c-jobData__badgeItem--occupation">{{ job.occupation_name }}</li>
							<li class="c-jobData__badgeItem--industry">{{ job.industry_name }}</li>
							<li class="c-jobData__badgeItem--prefecture">{{ job.prefecture_name }}</li>
							<li v-for="commitment in job.commitments" v-show="!isWidthS && job.commitments" :key="commitment" class="c-jobData__badgeItem--commitment">{{ commitment }}</li>
						</ul>

						<div class="p-jobDetail__mainArea">
							<div class="p-jobDetail__photoArea">
								<div class="p-jobDetail__photoMain">
									<img v-if="src" :src = src>
									<img v-else :src = "path + job.photo1">
								</div>
								<ul class="p-jobDetail__photoList">
									<li class="p-jobDetail__photoSub">
										<a @mouseover="setSrc" @click.prevent="open" :href="path + job.photo1">
											<img :src = "path + job.photo1">
											<span class="p-jobDetail__photoHover"><i class="fas fa-search-plus p-jobDetail__photoIcon"></i></span>
										</a>
									</li>
									<li class="p-jobDetail__photoSub">
										<a @mouseover="setSrc" @click.prevent="open" :href="path + job.photo2">
											<img :src = "path + job.photo2">
											<span class="p-jobDetail__photoHover"><i class="fas fa-search-plus p-jobDetail__photoIcon"></i></span>
										</a>
									</li>
									<li class="p-jobDetail__photoSub">
										<a @mouseover="setSrc" @click.prevent="open" :href="path + job.photo3">
											<img :src = "path + job.photo3">
											<span class="p-jobDetail__photoHover"><i class="fas fa-search-plus p-jobDetail__photoIcon"></i></span>
										</a>
									</li>
								</ul>
							</div>
							<div class="c-catchArea p-jobDetail__catchArea">
								<div class="c-catchArea__title">{{ job.job_catch }}</div>
								<div class="c-catchArea__text">{{ job.job_summary }}</div>
							</div>
						</div>
					</div>
				</div>

				<section class="c-section">
					<div class="c-section__title">募集要項</div>
					<div class="c-section__container p-jobDetail__sectionContainer u-pt0">
						<table class="p-jobDetail__table">
							<tbody>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">仕事内容<i class="fa-solid fa-briefcase"></i></th>
									<td class="p-jobDetail__td">
										<div class="p-jobDetail__tdJobName">{{ job.job_name }}</div>
										{{ job.job_description }}
									</td>
								</tr>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">応募条件<i class="fa-solid fa-table-list"></i></th>
									<td class="p-jobDetail__td">{{ job.application_conditions }}</td>
								</tr>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">雇用形態<i class="fa-regular fa-user"></i></th>
									<td class="p-jobDetail__td">{{ job.employment_status_name }}
										<div class="u-mt10">※6ヶ月間の試用期間があります。給与・福利厚生に変更はありません。</div>
									</td>
								</tr>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">勤務地<i class="fa-solid fa-location-dot"></i></th>
									<td class="p-jobDetail__td">{{ job.work_location }}</td>
								</tr>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">勤務時間<i class="fa-regular fa-clock"></i></th>
									<td class="p-jobDetail__td">{{ job.working_hours }}</td>
								</tr>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">年収<i class="fa-solid fa-yen-sign"></i></th>
									<td class="p-jobDetail__td">{{ priceFormat(job.annual_income) }}円〜</td>
								</tr>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">給与<i class="fa-brands fa-creative-commons-nd"></i></th>
									<td class="p-jobDetail__td">{{ job.salary }}</td>
								</tr>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">休日<i class="fa-regular fa-calendar-days"></i></th>
									<td class="p-jobDetail__td">{{ job.holiday }}</td>
								</tr>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">福利厚生<i class="fas fa-leaf"></i></th>
									<td class="p-jobDetail__td">{{ job.welfare }}</td>
								</tr>
								<tr class="p-jobDetail__tr">
									<th class="p-jobDetail__th">選考プロセス<i class="fa-solid fa-hand-pointer"></i></th>
									<td class="p-jobDetail__td">{{ job.selection }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</section>
				
				<section class="c-section" ref="companyInfo">
					<div class="c-section__title">会社情報</div>
					<div class="c-section__container p-jobDetail__sectionContainer">
						<table class="c-table__table p-jobDetail__companyTable">
							<tbody>
								<tr class="c-table__tr">
									<th class="c-table__th">会社名</th>
									<td class="c-table__td">{{ company.company_name }}</td>
								</tr>
								<tr class="c-table__tr">
									<th class="c-table__th">業種</th>
									<td class="c-table__td">{{ company.company_industry_name }}</td>
								</tr>
								<tr class="c-table__tr">
									<th class="c-table__th">住所</th>
									<td class="c-table__td">{{ company.company_address }}</td>
								</tr>
								<tr class="c-table__tr">
									<th class="c-table__th">事業内容</th>
									<td class="c-table__td">{{ company.company_business_content }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</section>

				<!-- ボタンエリア（未応募の場合に表示）-->
				<div class="p-jobDetail__fixedButtonArea" :class="{slide: isShowBtn}">
					<div class="p-jobDetail__fixedButtonBase">
						<div class="p-jobDetail__fixedButtonName">
							<div class="p-jobDetail__fixedButtonJob c-lineClamp--line1">{{ job.job_name }}</div>
							<div class="p-jobDetail__fixedButtonCompany">{{ company.company_name }}</div>
						</div>
						<div class="p-jobDetail__fixedButtonUnit">
							<!-- 気になるボタン-->
							<bookmark-btn-component v-if="isDataAcquired" :id="job.id"></bookmark-btn-component>
							<!-- 応募ボタン-->
							<button v-if="hasWebResume" @click.prevent="openApplyModal(job.id)" class="c-btn--apply" :disabled="isClicked"><i class="fa-regular fa-circle-check"></i>応募する</button>
							<!-- 公開時の変更 -->
							<!-- <button @click.prevent="openApplyModal(job.id)" class="c-btn--apply" :disabled="isClicked"><i class="fa-regular fa-circle-check"></i>応募する</button> -->
						</div>
					</div>
				</div>

			</template>
		</div> 
	</div>
</template>

<script>
import ApplyJob from '../mixin/ApplyJob.vue';
import BookmarkButton from '../components/BookmarkButton.vue';
import BreadCrumb from '../components/BreadCrumb.vue';
import Commitments from '../mixin/Commitments.vue';
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

	mixins: [ ApplyJob, Commitments, HandleResize, JobInfoProcessing ],

	data(){
		return {
			// id: this.$route.params.id,
			user: {},
			job: {},
			company: {},
			isApplied: false, // 自分が求人を応募したかの判定フラグ
			// referrer: this.$router.referrer['fullPath'],
			path: 'https://d38rk2cibjrg07.cloudfront.net/job_change_20/',
			scrollY: 0, // スクロール量
			// WEB履歴書の全登録フラグ
			hasWebResume: false,
			// 気になる＆応募ボタン表示フラグ
			isSlideBtn: false,
			// 非同期通信のデータ取得判定
			isDataAcquired: false,
		}
	},

	methods:{
		// スクロール量取得
		getScroll() {
			this.scrollY = window.scrollY

			if(this.$refs.companyInfo){
				// フッターまでスクロールしたら、気になる＆応募ボタン非表示
				if( this.$refs.companyInfo.getBoundingClientRect().bottom < (window.innerHeight + 100) ){
					this.isSlideBtn = false;
				}else{
					this.isSlideBtn = true;
				}
			}
		},

		// 画像切り替え
		setSrc: function (event) {
			const src = event.currentTarget.getAttribute('href');
			this.$store.commit('Modal/setSrc', { modalName : "jobDetail", src: src });
		},

		// LightBox
		open: function () {
			this.$store.commit('Modal/open', { modalName : "jobDetail"});
		},
		close: function () {
			this.$store.commit('Modal/close', { modalName : "jobDetail" });
		},

		// 求人情報取得
		getJob: async function () {
			// axiosで使うthisを格納
			const self = this;

			await axios.get('/job20/web/job/' + self.$route.params.id)
			.then(response => {
				if(response.data.successFlg){
					self.user = response.data.user;
					self.job = response.data.job;
					self.company = response.data.company;
					self.isApplied = response.data.isApplied;
					self.hasWebResume = response.data.hasWebResume;
					self.isDataAcquired = true;
					self.isLoading = false;
				}else{
					self.$router.push({ name: 'error'})
				}
			}).catch(error => console.log(error) );
		},
	},

	computed:{
		// モーダル表示判定フラグ
		isShowModal: function () {
			return this.$store.state.Modal.modal.jobDetail.isShow;
		},
		// 画像パス
		src: function () {
			return this.$store.state.Modal.modal.jobDetail.src;
		},
		// WEB履歴書必要アラートの表示判定
		isShowCard(){
			return !this.hasWebResume && !this.isApplied;
		},
		// 気になる＆応募ボタン表示判定
		isShowBtn(){
			return this.scrollY > 100 && !this.isApplied && this.isSlideBtn;
		},
	},

	created(){
		// 求人情報取得
		this.getJob();

		// 「求人を探す」「気になる求人」以外から遷移した場合、選択中のメニューを変更
		if( !(this.$store.state.Header.activeMenuNum === 1 || this.$store.state.Header.activeMenuNum === 2) ){
			this.$store.commit('Header/setActiveMenuNum', 0);			
		}

		// トップに戻るボタンの位置変更
		this.$store.commit('GoTop/setBottom', 90);	
		
		// スクロールイベント登録
		window.addEventListener("scroll", this.getScroll);
	},

	mounted() {
	},

	updated(){
		// 子コンポーネントがマウントされた後の処理
		// this.$nextTick(()=>{
		// });
	},

	beforeDestroy: function () {
		// トップに戻るボタンの位置変更
		this.$store.commit('GoTop/setBottom', 20);	
		// スクロールイベント削除
		window.removeEventListener("scroll", this.getScroll);
	}
}
</script>

<style scoped>
.slide{
	opacity: 1;
	transition: all .5s;
	transform: translateY(0);
}
</style>
