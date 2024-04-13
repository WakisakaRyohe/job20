<template>
	<div>
		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="title"></BreadCrumb>

		<div class="p-messages c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-envelope"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<!-- メッセージがある場合、メッセージ一覧を表示 -->
				<ul v-if="bords.length > 0" class="p-messages__list">
					<!-- メッセージをループ -->
					<li v-for="bord in sortedBords" class="p-messages__row">
						<router-link class="p-messages__item" :to="`/job20/bord/${bord.id}`">
							<!-- 日時 -->
							<div class="p-messages__date">{{ - diffDay(bord.messages[0].created_at) }}日前</div>
							<!-- アイコン画像  -->
							<div class="p-messages__imgCell">
								<div class="p-messages__img">
									<img :src="'https://d38rk2cibjrg07.cloudfront.net/job_change_20/' + bord.company.company_icon_image">
								</div>
							</div>
							<!-- テキスト -->
							<div class="p-messages__textCell">
								<div class="p-messages__textBody">
									<!-- 会社名 -->
									<div class="c-lineClamp--line1 p-messages__companyName">{{ bord.company.company_name }}</div>
									<!-- 求人名 -->
									<div class="c-lineClamp--line1to2 p-messages__jobTitle">{{ bord.job.job_name }}</div>
									<!-- メッセージ -->
									<div class="c-lineClamp--line2to3 p-messages__message">
										<template v-if="bord.messages[0].user_flg">あなた：</template>{{ bord.messages[0].message }}
									</div>
								</div>
							</div>
						</router-link>
					</li>
				</ul>

				<!-- メッセージがない場合 -->
				<section v-else class="c-section">
					<div class="c-section__container u-radius5">
						<div class="c-pageSet__contents">
							<div class="c-noData">※応募した会社からのメッセージはありません。</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</template>

<script>
import BreadCrumb from '../components/BreadCrumb.vue';
import Loading from '../components/Loading.vue';
import JobInfoProcessing from '../mixin/JobInfoProcessing.vue';

export default {
	components: {
		"Loading": Loading,
		"BreadCrumb": BreadCrumb,
	},
	mixins: [ JobInfoProcessing ],
	data(){
		return {
			title: 'メッセージ',
			bords: [], // 表示するメッセージの配列
			isLoading: true,
		}
	},
	methods:{
		// メッセージ一覧を取得
		getMessages: async function () {
			// axiosで使うthisを格納
			const self = this;

			await axios.get('/job20/web/messages')
			.then(response => {
				if(response.data.successFlg){
					self.bords = response.data.bords;
					self.isLoading = false;
				}else{
					self.$router.push({ name: 'error'})
				}
			}).catch(error => console.log(error) );
		},
	},

	computed: {
		sortedBords: function() {
			// sort()は元の配列の中身もソートされてしまうため、slice()で配列のコピーを作成してからソート
			let bords = this.bords.slice();

			// 掲示板をループ
			bords.forEach(bord => {
				// メッセージを新しい順でソート
				bord.messages.sort((a, b) => {
					return ( (a.created_at < b.created_at) ? 1 : -1 );
				});
			});
			// bords.forEach(bord => {
			// 	bord.messages.forEach(message => {
			// 		console.log(message.created_at);
			// 	});
			// });

			// メッセージが新しい順に掲示板をソート
			bords.sort((a, b) => {
				return ( (a.messages[0].created_at < b.messages[0].created_at) ? 1 : -1 );
			});
			// bords.forEach(bord => {
			// 	console.log(bord.messages[0].created_at);
			// });

			return bords;
		},
	},

	mounted(){
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 3);
		// メッセージ一覧を取得
		this.getMessages();
	},
}
</script>
