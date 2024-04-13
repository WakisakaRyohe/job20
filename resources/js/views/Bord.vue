<template>		
	<div>
		<!-- パンくずリスト -->
		<BreadCrumb :category="category" :title="title"></BreadCrumb>

		<div class="p-bord c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- フラッシュメッセージ -->
				<FlashMessage></FlashMessage>

				<!-- ページタイトル -->
				<div class="c-pageSet__title p-bord__pageTitle">
					<i class="fa-solid fa-envelope"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>
				<div class="p-bord__navi">
					<router-link class="p-bord__jobLink" :to="`/job20/job/${bord.job.id}`">
						<i class="fa-solid fa-list"></i>
						<span>応募先の求人</span>
					</router-link>
				</div>

				<section class="c-section u-mb0">
					<!-- メッセージ一覧 -->
					<div class="c-section__container p-bord__container">
						<ul class="p-bord__messageList">

							<!-- 自分の初回メッセージ -->
							<li class="p-bord__messageItem">
								<div class="p-bord__messageUnit--first">
									<div class="p-bord__messageValue--first">【{{ bord.job.job_name }}】へ応募しました。</div>
									<!-- 日付 -->
									<div class="p-bord__date">
										{{ dateFormatMD(bord.created_at) }}<br class="u-tabHidden">{{ dateFormatHM(bord.created_at) }}
									</div>
								</div>
							</li>

							<!-- メッセージをループ -->
							<template v-for="message in bord.messages">
								<!-- 自分のメッセージ -->
								<li v-if="message.user_flg" class="p-bord__messageItem">
									<div class="p-bord__messageUnit">
										<div class="p-bord__messageValue">{{ message.message }}</div>
										<!-- 日付 -->
										<div class="p-bord__date">
											{{ dateFormatMD(message.created_at) }}<br class="u-tabHidden">{{ dateFormatHM(message.created_at) }}
										</div>
									</div>
								</li>

								<!-- 会社のメッセージ -->
								<li v-else class="p-bord__messageItem--company">
									<!-- アイコン画像  -->
									<div class="p-bord__imgCell">
										<span class="p-bord__img">
											<img :src="'https://d38rk2cibjrg07.cloudfront.net/job_change_20/' + bord.company.company_icon_image">
										</span>
									</div>
									<div class="p-bord__messageUnit--company">
										<div class="p-bord__messageValue">{{ message.message }}</div>
										<!-- 日付 -->
										<div class="p-bord__date--company">
											{{ dateFormatMD(message.created_at) }}<br class="u-tabHidden">{{ dateFormatHM(message.created_at) }}
										</div>
									</div>
								</li>
							</template>
						</ul>
					</div>
				</section>

				<!-- ここまで自動スクロール -->
				<div class="p-bord__scroll" ref="scroll"></div>

				<!-- メッセージ投稿フォーム -->
				<form class="p-bord__form">
					<!-- csrf対策 -->
					<input type="hidden" name="_token" :value="csrf">

					<div class="p-bord__formCell">
						<textarea @keyup="formChange()" v-model="newMessage" ref="newMessage" name="newMessage" 
							:style="[ height, {backgroundColor: inputColor( getErrorMessage(errors.newMessage) )}]" 
							class="p-bord__textArea" placeholder="メッセージを入力">
						</textarea>
						<div class="c-table__errMsg" v-if="getErrorMessage(errors.newMessage)">{{ getErrorMessage(errors.newMessage) }}</div>
					</div>
					<div class="p-bord__btnCell">
						<a @click.prevent="formSubmit(), createMessage()" class="p-bord__btn" :class="[{disabled: isDisabled}, {pointerON: !isDisabled}]">
							<i class="fa-solid fa-location-arrow"></i>
						</a>
					</div>
				</form>
			</div>  
		</div>  
	</div>  
</template>

<script>
import BookmarkButton from '../components/BookmarkButton.vue';
import BreadCrumb from '../components/BreadCrumb.vue';
import FlashMessage from '../components/FlashMessage.vue';
import FormChanged from '../mixin/FormChanged.vue';
import JobInfoProcessing from '../mixin/JobInfoProcessing.vue';
import Loading from '../components/Loading.vue';
import Modal from '../components/Modal.vue';
import Validation from '../mixin/Validation.vue';
import ValidationBord from '../mixin/ValidationBord.vue';

export default {
	components: {
		"bookmark-btn-component": BookmarkButton,
		"BreadCrumb": BreadCrumb,
		"FlashMessage": FlashMessage,
		"Loading": Loading,
		"Modal": Modal,
	},

	mixins: [ FormChanged, JobInfoProcessing, Validation, ValidationBord ],

	props: [
		'id',
	],

	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// パンくずリストに渡すデータ
			title: '',
			category: {
				id: 3,
				name: 'メッセージ',
				path: '/job20/messages',
			},
			bord: {},
			newMessage: '',
			newMessageHeight: "auto", // "50px",
			errors: {
				newMessage: [],
			},	
			isLoading: true,
			alertType: 'send',
		}
	},

	methods:{
		// テキストエリアの高さ変更
		resizeNewMessageArea(){
			if(this.$refs.newMessage){
				this.newMessageHeight = "auto";
				this.$nextTick(()=>{
					this.newMessageHeight = this.$refs.newMessage.scrollHeight + 'px';
				});
			}
		},

		// ページ最下部までスクロール
		scrollToEnd() {
			let target = this.$refs.scroll;
			if (!target) return;
			target.scrollIntoView(false);

			// https://qiita.com/amamamaou/items/728d571d508347b2bc82
		},

		// メッセージ情報取得
		getBord: async function () {
			// axiosで使うthisを格納
			const self = this;

			await axios.get('/job20/web/bord/' + this.id)
			.then(response => {
				if(response.data.successFlg){
					self.bord = response.data.bord;
					self.title = self.bord.company.company_name + 'とのメッセージ';
					self.isLoading = false;
				}else{
					self.$router.push({ name: 'error' });	
				}
			}).catch(error => console.log(error) );
		},

		// メッセージ投稿
		createMessage: async function (){
			// ボタン連打を無効化
			this.isDisabled = true;

			// 連想配列
			const params = {
				bord: this.bord,
				newMessage: this.newMessage,
				user_flg: true,
				// user_flg: false,
			};

			// エラー配列初期化
			this.errors = {};
			
			// axiosで使うthisを格納
			const self = this;

			await axios.post('/job20/web/bord/' + this.id, params)
			.then(response => {
				if(response.data.successFlg){
					self.bord.messages.push(response.data.message);
					self.newMessage = "";
					self.scrollToEnd()

					// vuexからメッセージ情報削除
					let newArray = self.newMessagesArray.filter(obj => obj.bordID != self.id);
					self.$store.commit('Bord/setNewMessagesArray', newArray);
				}else{
					self.$router.push({ name: 'error' });	
				}
			})
			.catch(error => {
				let errors = {};
				// 返却データを配列化
				for(let key in error.response.data.errors) {
					errors[key] = error.response.data.errors[key];
				}
				// dataプロパティに代入
				self.errors = errors;

				// フォーム編集フラグは有効のままにする
				self.formChange();
			});
		},

		// vuexにメッセージ情報を登録
		inputNewMessage(){
			// 他の掲示板のメッセージ配列を取得
			let newArray = this.newMessagesArray.filter(obj => obj.bordID != this.id);

			// 今の掲示板のメッセージがある場合
			if(this.newMessage){
				// メッセージ情報を定義
				const newMessageObj = {
					bordID: this.id,
					newMessage: this.newMessage,
					newMessageHeight: this.newMessageHeight,
				};

				// 他の掲示板のメッセージ配列に追加
				newArray.push(newMessageObj);
			}

			// vuexにメッセージ配列を登録
			this.$store.commit('Bord/setNewMessagesArray', newArray);
		},
	},

	computed: {
		// csrf対策
		computedCsrf() {},

		// vuexからメッセージ配列を取得
		newMessagesArray: {
			get() {
				return this.$store.state.Bord.newMessagesArray;
			},
		},

		// テキストエリアの高さ返却
		height(){
			return {
				"height": this.newMessageHeight,
			}
		},
	},

	mounted(){
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 3);
		// メッセージ情報取得
		this.getBord();

		if(this.newMessagesArray.length > 0){
			// vuexから、今の掲示板に該当するメッセージ情報を取得(該当するメッセージがないときは「null」が入る)
			const newMessageObj = this.newMessagesArray.find(obj => obj.bordID == this.id) ?? null;

			// 該当するメッセージがあれば情報を代入
			if(newMessageObj !== null){
				this.newMessage = newMessageObj.newMessage;
				this.newMessageHeight = newMessageObj.newMessageHeight;
			}
		}
	},

	// メッセージが投稿されたら、ページ最下部までスクロール
	updated() {
		this.scrollToEnd()
	},

	destroyed(){
		this.inputNewMessage();
	}
}
</script>

<style scoped>
.pointerON{
	pointer-events: auto;
}
</style>
