<template>
	<div>
		<!-- フラッシュメッセージ -->
		<FlashMessage></FlashMessage>

		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="title"></BreadCrumb>
	
		<div class="p-setting c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title p-setting__title">
					<i class="fa-solid fa-gear"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<div class="c-pageSet__navi p-setting__navi">
					<div class="c-pageSet__catch">ご登録内容の確認、変更ができます。</div>
					<div class="c-pageSet__copy">
						登録内容の確認、変更をするには、各項目の<span class="u-fw-bold">「変更する」</span>ボタンをクリックしてください。
					</div>
				</div>

				<section class="c-section">
					<h3 class="c-section__title--grad">メールアドレス変更</h3>
					<div class="c-section__container">
						<table class="c-table">
							<tbody>
								<tr class="c-table__tr">
									<th class="c-table__th--sub p-setting__th">メールアドレス</th>
									<td class="c-table__td--sub">{{ user.email }}</td>
									<td class="c-table__td p-setting__btnBase">
										<router-link class="c-btn p-setting__btn" :to="`/job20/change_email`">
											<i class="fa-solid fa-arrow-rotate-right"></i>変更する
										</router-link>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</section>

				<section class="c-section">
					<h3 class="c-section__title--grad">パスワード変更</h3>
					<div class="c-section__container">
						<table class="c-table">
							<tbody>
								<tr class="c-table__tr">
									<th class="c-table__th--sub p-setting__th">パスワード</th>
									<td class="c-table__td--sub">********</td>
									<td class="c-table__td p-setting__btnBase">
										<router-link class="c-btn p-setting__btn" :to="`/job20/change_password`">
											<i class="fa-solid fa-arrow-rotate-right"></i>変更する
										</router-link>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</section>

				<section class="c-section">
					<h3 class="c-section__title p-setting__withdrawalTitle">退会の手続き</h3>
					<div class="c-section__container">
						<table class="c-table">
							<tbody>
								<tr class="c-table__tr">
									<th class="c-table__th--sub p-setting__withdrawalTh">ジョブインフォ20からの<br class="u-spShow">退会手続きをおこないます。</th>
									<td class="c-table__td p-setting__btnBase">
										<router-link class="c-btn p-setting__withdrawalBtn" :to="`/job20/withdrawal`">
											<span>退会手続き</span>
										</router-link>
									</td>
								</tr>
							</tbody>
						</table>
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

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"FlashMessage": FlashMessage,
		"Loading": Loading,
	},
	mixins: [],
	data(){
		return {
			// パンくずリストに渡すデータ
			title: '各種設定の確認・変更',
			// フォームデータ
			user: {
				email: '',
			},
			isLoading: true,
		}
	},

	methods: {
		// ユーザー情報を取得
		getUser(){
			// axiosで使うthisを格納
			const self = this;

			axios.get('/job20/web/setting')
			.then(response => {
				if(response.data.successFlg){
					self.user = response.data.user;
					self.isLoading = false;
				}else{
					self.$router.push({ name: 'error' });
				}
			}).catch(error => console.log(error) );
		},
	},

	computed: {
	},

	mounted(){
		this.getUser();
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 5);
	},
}
</script>
