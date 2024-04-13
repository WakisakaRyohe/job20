<template>
	<div>
		<!-- フラッシュメッセージ -->
		<FlashMessage></FlashMessage>

		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="title"></BreadCrumb>

		<div class="p-login c-inner--m">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-right-to-bracket"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<div v-if="alert" class="c-pageSet__navi--alert">
					<div class="c-pageSet__catch--alert"><i class="fa-solid fa-circle-exclamation"></i>
						<span class="c-pageSet__catchText">{{ alert }}</span>
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
											<input @keyup="formChange()" v-model.lazy="form.email" class="c-input" id="email" 
												name="email" type="email" placeholder="メールアドレスを入力" 
												:style="{backgroundColor: inputColor( getErrorMessage(errors.email) )}" autocomplete="on">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.email)">{{ getErrorMessage(errors.email) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">パスワード</span>
											<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-table__inputArea">
												<input @keyup="formChange()" v-model.lazy="form.password" class="c-input" id="password" 
													name="password" :type="inputTypePassword" :placeholder="placeholderPassword" 
													:style="{backgroundColor: inputColor( getErrorMessage(errors.password) )}" autocomplete="on">
													<!-- onpaste="return false"  -->
												<i v-if="inputTypePassword === 'password'" class="fa-regular fa-eye c-input__togglePasswordIcon" @click="changeTogglePassword"></i>
												<i v-else class="fa-regular fa-eye-slash c-input__togglePasswordIcon" @click="changeTogglePassword"></i>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.password)">{{ getErrorMessage(errors.password) }}</div>
										</td>
									</tr>
								</tbody>
							</table>

							<!-- パスワードリマインダー画面へのリンク -->
							<div class="c-form__alignCenterArea">
								※パスワードをお忘れの方は<router-link :to="`/job20/password_remind_send`" class="c-form__link u-ml5">こちら</router-link>
							</div>

							<!-- ログインボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), login(referrer)" class="c-btn--m c-btn--subColor" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClicked} ]" :disabled="isDisabled" value="ログイン">
								</div>
							</div>

							<!-- ログインしたままにするチェックボックス -->
							<div class="c-form__alignCenterArea u-mt10">
								<label class="c-label" for="remember">
									<input v-model="form.remember" class="u-none" type="checkbox" 
										value="" name="remember" id="remember" @change="changeActiveCheck">
									<i v-if="isActiveCheck" class="fa-solid fa-check c-checkmark"></i>
									<span>自動ログイン</span>
								</label>
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
import Placeholder from '../mixin/Placeholder.vue';
import TogglePassword from '../mixin/TogglePassword.vue';
import Validation from '../mixin/Validation.vue';
import ValidationLogin from '../mixin/ValidationLogin.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"FlashMessage": FlashMessage,
		"Loading": Loading,
	},

	mixins: [ FormChanged, Placeholder, TogglePassword, Validation, ValidationLogin ],

	props: [
		'referrer',
	],

	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// タイトル
			title: 'ログイン',
			// フォームデータ
			form: {
				email: '',
				password: '11111111',
				// 公開時の変更
				// password: '',
				remember: false,
			},
			// エラーオブジェクト
			errors: {
				email: [],
				password: [],
			},
			alert: '',
			width: 0,
			// 処理中の画面表示
			isLoading: false,
			// チェックマークの表示切り替え
			isActiveCheck: false,
			// 離脱アラートのタイプ
			alertType: 'send',
		}
	},

	methods:{
		// チェックマークの表示切り替え
		changeActiveCheck(){
			this.isActiveCheck = !this.isActiveCheck;
		},

		// ログイン処理
		login: async function(referrer){
			// 処理中の画面表示
			this.isLoading = true;

			// ボタン連打を無効化
			this.isClicked = true;

			// エラー配列初期化
			this.errors = {};

			// Laravelのログイン用のURL
			const url = '/job20/login';

			// 送信データを用意
			const params = {
				email: this.form.email,
				password: this.form.password,
				remember: this.form.remember,
			};

			// axiosで使うthisを格納
			const self = this;

			// 非同期処理
			await axios.post(url, params)
			// ログイン成功時の処理
			.then(function(response){
				// ローカルストレージにログイン情報とフラッシュメッセージを保存
				localStorage.setItem('isLogin', true);
				localStorage.setItem(['flash_message'],['ログインしました。']);
				// localStorage.setItem('autoLogin', self.remember);

				// 自動ログインにチェックがない場合、ログイン期限を2時間に設定
				if(!self.form.remember){
					const loginLimit = Date.now() + (60 * 60 * 2 * 1000);
					localStorage.setItem('loginLimit', loginLimit);
				}

				if(referrer){
					// 遷移元へリダイレクト
					location.href = referrer;
				}else{
					// 検索ページへリダイレクト
					location.href = '/job20/search';
				}

				// vuexのデータ削除
				self.$store.commit('Login/setEmail', '');
			})
			// ログイン失敗時の処理
			.catch(error => {
				let errors = {};
				// 返却データを配列化
				for(let key in error.response.data.errors) {
					errors[key] = error.response.data.errors[key];

					if(errors[key][0] === 'メールアドレスまたはパスワードが違います。'){
						self.alert = 'メールアドレスまたはパスワードが違います。';
						errors[key] = [];
					}
				}
								
				// これがないと、パスワード入力でリアルタイムバリデーションが作動しない
				// Laravelのバリデーションでエラーメッセージを格納した後、他のエラー配列が定義されていないため
				if(errors.password === undefined){
					errors.password = [];
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

	created(){
		// vuexのデータ取得
		this.form.email = this.$store.state.Login.email;
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 8);
	}
}
</script>
