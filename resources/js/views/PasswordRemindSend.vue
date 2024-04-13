<template>
	<div>
		<!-- フラッシュメッセージ -->
		<FlashMessage></FlashMessage>

		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="title"></BreadCrumb>

		<div class="p-passwordRemindSend c-inner--m">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-key"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<div class="c-pageSet__navi">
					<div class="c-pageSet__catch">パスワード再発行用のURLと認証キーをお送り致します。</div>
					<div class="c-pageSet__copy">メールアドレスを入力のうえ、ページ下の「<span class="u-fw-bold">送信する</span>」ボタンをクリックしてください。<br>
						<div class="c-attention u-mt10">※登録したメールアドレスが分からない場合は、<router-link class="c-pageSet__link" :to="`/job20/contact`">お問い合わせフォーム</router-link>からお問い合わせください。</div>
					</div>
				</div>

				<section class="c-section">
					<div class="c-section__container u-radius5">
						<form class="c-form">
							<!-- csrf対策 -->
							<input type="hidden" name="_token" :value="csrf">

							<table class="c-table">
								<tbody>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">メールアドレス</span>
											<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<input @keyup="formChange()" v-model.lazy="email" class="c-input" id="email" 
												name="email" type="email" placeholder="メールアドレスを入力" 
												:style="{backgroundColor: inputColor( getErrorMessage(errors_email) )}" autocomplete="on">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors_email)">{{ getErrorMessage(errors_email) }}</div>
										</td>
									</tr>
								</tbody>
							</table>

							<!-- メール送信ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), sendMail()" class="c-btn--m c-btn--subColor" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClicked} ]" :disabled="isDisabled" value="送信する">
								</div>
							</div>
						</form>
					</div>
				</section>
			</div>
		</div>
	</div>
</template>

<script>
import BreadCrumb from '../components/BreadCrumb.vue';
import FlashMessage from '../components/FlashMessage.vue';
import FormChanged from '../mixin/FormChanged.vue';
import Loading from '../components/Loading.vue';
import Modal from '../components/Modal.vue';
import Validation from '../mixin/Validation.vue';
import ValidationPasswordRemindSend from '../mixin/ValidationPasswordRemindSend.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"FlashMessage": FlashMessage,
		"Loading": Loading,
		"Modal": Modal,
	},

	mixins: [ FormChanged, Validation, ValidationPasswordRemindSend ],

	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// タイトル
			title: 'パスワード再発行メール送信',
			// 離脱アラートのタイプ
			alertType: 'send',
			// 処理中の画面表示
			isLoading: false,
		}
	},

	methods:{
		// メール送信処理
		sendMail: async function () {
			// 処理中の画面表示
			this.isLoading = true;

			// ボタン連打を無効化
			this.isClicked = true;

			// エラー配列初期化
			this.errors = {};

			// axiosで使うthisを格納
			const self = this;

			// 非同期処理
			await axios.post('/job20/web/password_remind_send', {
				email: this.email,
			})
			.then(function(response){
				if(response.data.successFlg){
					// フラッシュメッセージ表示
					self.$store.commit('Modal/setFlash', true);
					self.$store.commit('Modal/setMessage', 'パスワード再設定用メールを送信しました。');
					self.$router.push({ name: 'password_remind_receive' });
					
					// パスワード再発行認証が終わるまで、データ削除しない
					
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
				// vuexに代入
				self.$store.commit('PasswordRemindSend/set_errors', errors);

				// フォーム編集フラグは有効のままにする
				self.formChange();

				// 処理中の画面を非表示
				self.isLoading = false;

				// トップにスクロール
				window.scrollTo({top: 0, behavior: 'smooth'})
			});

			// ボタンを初期状態に戻す
			this.isDisabled = true;
			this.isClicked = false;
		}
	},

	computed:{
		computedCsrf() {},

		// 入力データをバインディング
		email: {
			get() {
				return this.$store.state.PasswordRemindSend.email;
			},
			set(str) {
				this.$store.commit('PasswordRemindSend/setEmail', str);
			}
		},
		errors_email: {
			get() {
				return this.$store.state.PasswordRemindSend.errors.email;
			},
		},
	},

	mounted(){
		// メールアドレスが空の場合は、エラーメッセージ削除
		// watchが初回から有効だが、「メールアドレスは入力必須です。」も削除する
		if(this.email === ''){
			this.$store.commit('PasswordRemindSend/reset_errors_email', []);
		}
	}
}
</script>
