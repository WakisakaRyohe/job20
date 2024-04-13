<template>
	<div>
		<!-- フラッシュメッセージ -->
		<FlashMessage></FlashMessage>

		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="title"></BreadCrumb>

		<div class="p-webResume c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-file-lines"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<section class="c-section">
					<div class="c-section__tab">
						<div class="c-tab">
							<!-- PC用タブ -->
							<ul class="c-tab__menu" :class="{'reverse': isReverse}">
								<li class="c-tab__container">
									<ul class="c-tab__grorp">
										<li class="c-tab__item" @click="isSelect(1)" :class="{'active': isActive == 1}">
											<span class="c-tab__title" :class="{'active': isActive == 1}">
												<div class="c-tab__inner">
													<span class="u-mr5">プロフィール</span>
													<span v-if="user.profile" class="c-tab__iconDone"><span class="c-tab__hidden">登録</span>済</span>
													<span v-else class="c-tab__iconUnregistered">未<span class="c-tab__hidden">登録</span></span>
												</div>
											</span>
										</li>
										<li class="c-tab__item" @click="isSelect(2)" :class="{'active': isActive == 2}">
											<span class="c-tab__title" :class="{'active': isActive == 2}">
												<div class="c-tab__inner">
													<span class="u-mr5">職務経歴</span>
													<span v-if="user.job_careers.length > 0" class="c-tab__iconDone"><span class="c-tab__hidden">登録</span>済</span>
													<span v-else class="c-tab__iconUnregistered">未<span class="c-tab__hidden">登録</span></span>
												</div>
											</span>
										</li>
									</ul>
								</li>
								<li class="c-tab__container">
									<ul class="c-tab__grorp">
										<li class="c-tab__item" @click="isSelect(3)" :class="{'active': isActive == 3}">
											<span class="c-tab__title" :class="{'active': isActive == 3}">
												<div class="c-tab__inner">
													<span class="u-mr5">資格・スキル</span>
													<span v-if="user.skill" class="c-tab__iconDone"><span class="c-tab__hidden">登録</span>済</span>
													<span v-else class="c-tab__iconUnregistered">未<span class="c-tab__hidden">登録</span></span>
												</div>
											</span>
										</li>
										<li class="c-tab__item" @click="isSelect(4)" :class="{'active': isActive == 4}">
											<span class="c-tab__title" :class="{'active': isActive == 4}">
												<div class="c-tab__inner">
													<span class="u-mr5">自己PR</span>
													<span v-if="user.self_promotion" class="c-tab__iconDone"><span class="c-tab__hidden">登録</span>済</span>
													<span v-else class="c-tab__iconUnregistered">未<span class="c-tab__hidden">登録</span></span>
												</div>
											</span>
										</li>
									</ul>
								</li>
							</ul>

							<!-- スマホ用タブ -->
							<ul class="c-tab__menu--SP">
								<li class="c-tab__item" @click="isSelect(1)" :class="{'active': isActive == 1}">
									<span class="c-tab__title" :class="{'active': isActive == 1}">
										<div class="c-tab__inner">
											<span class="u-mr5">プロフィール</span>
											<span v-if="user.profile" class="c-tab__iconDone"><span class="c-tab__hidden">登録</span>済</span>
											<span v-else class="c-tab__iconUnregistered">未<span class="c-tab__hidden">登録</span></span>
										</div>
									</span>
								</li>
								<li class="c-tab__item" @click="isSelect(2)" :class="{'active': isActive == 2}">
									<span class="c-tab__title" :class="{'active': isActive == 2}">
										<div class="c-tab__inner">
											<span class="u-mr5">職務経歴</span>
											<span v-if="user.job_careers.length > 0" class="c-tab__iconDone"><span class="c-tab__hidden">登録</span>済</span>
											<span v-else class="c-tab__iconUnregistered">未<span class="c-tab__hidden">登録</span></span>
										</div>
									</span>
								</li>
								<li class="c-tab__item" @click="isSelect(3)" :class="{'active': isActive == 3}">
									<span class="c-tab__title" :class="{'active': isActive == 3}">
										<div class="c-tab__inner">
											<span class="u-mr5">資格・スキル</span>
											<span v-if="user.skill" class="c-tab__iconDone"><span class="c-tab__hidden">登録</span>済</span>
											<span v-else class="c-tab__iconUnregistered">未<span class="c-tab__hidden">登録</span></span>
										</div>
									</span>
								</li>
								<li class="c-tab__item" @click="isSelect(4)" :class="{'active': isActive == 4}">
									<span class="c-tab__title" :class="{'active': isActive == 4}">
										<div class="c-tab__inner">
											<span class="u-mr5">自己PR</span>
											<span v-if="user.self_promotion" class="c-tab__iconDone"><span class="c-tab__hidden">登録</span>済</span>
											<span v-else class="c-tab__iconUnregistered">未<span class="c-tab__hidden">登録</span></span>
										</div>
									</span>
								</li>
							</ul>
						</div>
					</div>

					<div class="c-section__container p-webResume__sectionContainer">
						<template v-if="isActive == 1">
							<Profile :profile="user.profile"></Profile>
						</template>
						<template v-else-if="isActive == 2">
							<JobCareer :job_careers="user.job_careers"></JobCareer>
						</template>
						<template v-else-if="isActive == 3">
							<Skill :skill="user.skill"></Skill>
						</template>
						<template v-else-if="isActive == 4">
							<SelfPromotion :self_promotion ="user.self_promotion"></SelfPromotion>
						</template>
					</div>
				</section>
			</div>
		</div>
	</div>
</template>

<script>
import BreadCrumb from '../components/BreadCrumb.vue';
import FlashMessage from '../components/FlashMessage.vue';
import Loading from '../components/Loading.vue';
import JobCareer from '../components/JobCareer.vue';
import Modal from '../components/Modal.vue';
import Profile from '../components/Profile.vue';
import SelfPromotion from '../components/SelfPromotion.vue';
import Skill from '../components/Skill.vue';
import TogglePassword from '../mixin/TogglePassword.vue';
import Validation from '../mixin/Validation.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"FlashMessage": FlashMessage,
		"JobCareer": JobCareer,
		"Loading": Loading,
		"Modal": Modal,
		"Profile": Profile,
		"SelfPromotion": SelfPromotion,
		"Skill": Skill,
	},

	mixins: [ TogglePassword, Validation ],

	props: [
		'propNum',
	],
	
	data(){
		return {
			title: 'WEB履歴書',
			user: {}, // ユーザーデータ
			isLoading: true,
		}
	},

	// タブ切り替え
	watch: {
		isActive: function(){
			if(this.isActive == 1 || this.isActive == 2){
				this.$store.commit('WebResume/set_isReverse', true);
			}else if(this.isActive == 3 || this.isActive == 4){
				this.$store.commit('WebResume/set_isReverse', false);
			}
		},
	},

	methods:{
		isSelect: function (num) {
			this.$store.commit('WebResume/set_isActive', num);
        },

		// ユーザー情報を取得
		getUser: async function (){
			// axiosで使うthisを格納
			const self = this;

			await axios.get('/job20/web/web_resume')
			.then(response => {
				if(response.data.successFlg){
					if(response.data.user){
						self.user = response.data.user;
						self.isLoading = false;
					}
				}else{
					self.$router.push({ name: 'error' });
				}
			}).catch(error => console.log(error) );

			if(this.fromEditJobCareer){
				// 職務経歴のデータ削除
				this.$store.commit('EditJobCareer/set_job_careers_company_name', '');
				this.$store.commit('EditJobCareer/set_year_of_joining', '');
				this.$store.commit('EditJobCareer/set_month_of_joining', '');
				this.$store.commit('EditJobCareer/set_year_of_retirement', '');
				this.$store.commit('EditJobCareer/set_month_of_retirement', '');
				this.$store.commit('EditJobCareer/set_employment_status', '');
				this.$store.commit('EditJobCareer/set_job_careers_industry', '');
				this.$store.commit('EditJobCareer/set_number_of_employees', '');
				this.$store.commit('EditJobCareer/set_department_or_post', '');
				this.$store.commit('EditJobCareer/set_job_details', '');					
				this.$store.commit('EditJobCareer/set_textarea_height', "auto");				
			}
		}
	},

	computed: {
		isActive: {
			get() {
				return this.$store.state.WebResume.isActive;
			},
		},
		isReverse: {
			get() {
				return this.$store.state.WebResume.isReverse;
			},
		},
		fromEditJobCareer: {
			get() {
				return this.$store.state.WebResume.fromEditJobCareer;
			},
		},
	},

	mounted(){
			// 選択中のメニューを変更
			this.$store.commit('Header/setActiveMenuNum', 4);
		// 各編集ページから遷移時、編集データがわかるようにタブを切り替えておく
		if(this.propNum){
			this.$store.commit('WebResume/set_isActive', this.propNum);
		}
		// ユーザー情報を取得
		this.getUser();
	},
}
</script>