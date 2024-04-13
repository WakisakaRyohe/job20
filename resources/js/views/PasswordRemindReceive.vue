<template>
	<div>
		<!-- フラッシュメッセージ -->
		<FlashMessage></FlashMessage>
		
		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="title"></BreadCrumb>

		<div class="p-passwordRemindReceive c-inner--m">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-key"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<div class="c-pageSet__navi">
					<div class="c-pageSet__catch">パスワード再発行用の認証キーを照合します。</div>
					<div class="c-pageSet__copy u-alignLeft">
						【パスワード再発行認証】メール内にある「認証キー」を入力し、「<span class="u-fw-bold">再発行する</span>」ボタンをクリックしてください。<br class="u-tabShow">【パスワード再発行完了メール】を送信致します。
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
												<span class="c-table__thTitle">認証キー</span>
											<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<input @keyup="formChange()" v-model.lazy="auth_key" class="c-input" id="auth_key" 
												name="auth_key" type="text" placeholder="認証キーを入力してください" 
												:style="{backgroundColor: inputColor( getErrorMessage(errors.auth_key) )}" autocomplete="on">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.auth_key)">{{ getErrorMessage(errors.auth_key) }}</div>
										</td>
									</tr>
								</tbody>
							</table>

							<!-- パスワード再発行ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), passwordReset()" class="c-btn--m c-btn--subColor" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClicked} ]" :disabled="isDisabled" value="再発行する">
								</div>
							</div>

							<!-- パスワードリマインダー画面へのリンク -->
							<div class="c-form__alignCenterArea">
								※パスワード再発行メールを再度送信する場合は<router-link :to="`/job20/password_remind_send`" class="c-form__link">こちら</router-link>
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
import ValidationPasswordRemindReceive from '../mixin/ValidationPasswordRemindReceive.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"FlashMessage": FlashMessage,
		"Loading": Loading,
		"Modal": Modal,
	},

	mixins: [ FormChanged, Validation, ValidationPasswordRemindReceive ],

	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// タイトル
			title: 'パスワード再発行認証',
			// フォームデータ
			auth_key: '',
			// エラーオブジェクト
			errors: {
				auth_key: '',
			},
			isLoading: true,
			// バリデーション用の認証キー
			session_auth_key: '',
		}
	},

	methods:{
		// セッションデータ取得
		getSession: async function () {
			// axiosで使うthisを格納
			const self = this;

			await axios.get('/job20/web/password_remind_edit')
			.then(function(response){
				if(response.data.successFlg){
					// リアルタイムバリデーション用に、認証キーのデータ取得
					self.session_auth_key = response.data.session_auth_key;
					self.isLoading = false;

				}else{
					// 認証キー取得に失敗した場合
					if(response.data.limitOverFlg){
						// 遷移先でフラッシュメッセージ表示
						self.$store.commit('Modal/setFlash', true);
						self.$store.commit('Modal/setMessage', '不正なリクエストです。もう１度やり直してください。');
						self.$router.push({ name: 'password_remind_send'});
						return;
					}
					// サーバーエラーの場合
					self.$router.push({ name: 'error' });
				}

			}).catch(error => console.log(error) );
		},

		// パスワードリセット処理
		passwordReset: async function (){
			// 処理中の画面表示
			this.isLoading = true;

			// ボタン連打を無効化
			this.isClicked = true;

			// エラー配列初期化
			this.errors = {};

			const param = {
				auth_key: this.auth_key,
			};

			// axiosで使うthisを格納
			const self = this;

			// 非同期処理
			await axios.post('/job20/web/password_remind_receive', param)
			.then(function(response){
				// 遷移先でフラッシュメッセージ表示
				self.$store.commit('Modal/setFlash', true);

				if(response.data.successFlg){
					if(localStorage.getItem('isLogin') === 'true'){
						//  ログイン済みユーザーの場合、パスワード再設定ページへ遷移
						self.$store.commit('Modal/setMessage', 'パスワードを再設定しました。');
						self.$router.push({ name: 'change_password'});
					}else{
						//  未ログインユーザーの場合、ログインページへ遷移
						self.$store.commit('Modal/setMessage', 'パスワードを再設定しました。<br>新しいパスワードでログインしてください。');
						self.$router.push({ name: 'login'});
					}

					// vuexのデータ削除
					self.$store.commit('PasswordRemindSend/setEmail', '');
					self.$store.commit('PasswordRemindSend/reset_errors_email', []);

				}else{
					// 有効期限切れの場合、パスワード再設定メール送信フォームに遷移
					if(response.data.limitOverFlg){
						self.$store.commit('Modal/setMessage', 'パスワード再発行の有効期限切れです。もう１度やり直してください。');
						self.$router.push({ name: 'password_remind_send'})
						return;
					}

					self.$store.commit('Modal/setMessage', 'パスワード再設定処理に失敗しました。もう１度やり直してください。');
					self.$router.push({ name: 'password_remind_send'});
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
	},

	mounted(){
		// セッションデータ取得
		this.getSession();
	}
}
</script>
