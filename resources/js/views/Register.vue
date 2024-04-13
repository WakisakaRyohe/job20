<template>
	<div>
		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="title"></BreadCrumb>

		<div class="p-register c-inner--m">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-user"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
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
											<span class="c-table__thTitle">メールアドレス</span>
											<span class="c-table__badge--require">必須</span>
										</th>
										<td class="c-table__td">
											<input @keyup="formChange()" v-model.lazy="email" class="c-input" id="email" 
												name="email" type="email" placeholder="メールアドレスを入力" 
												:style="{backgroundColor: inputColor( getErrorMessage(errors.email) )}" autocomplete="on"> 
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.email)">{{ getErrorMessage(errors.email) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<span class="c-table__thTitle">パスワード</span>
											<span class="c-table__badge--require">必須</span>
										</th>
										<td class="c-table__td">
											<div class="c-table__inputArea">
												<input @keyup="formChange()" v-model.lazy="password" class="c-input" id="password" 
													name="password" :type="inputTypePassword" :placeholder="placeholderPassword" 
													:style="{backgroundColor: inputColor( getErrorMessage(errors.password) )}" autocomplete="on"> 
												<i v-if="inputTypePassword === 'password'" class="fa-regular fa-eye c-input__togglePasswordIcon" @click="changeTogglePassword"></i>
												<i v-else class="fa-regular fa-eye-slash c-input__togglePasswordIcon" @click="changeTogglePassword"></i>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.password)">{{ getErrorMessage(errors.password) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<span class="c-table__thTitle">パスワード(確認)</span>
											<span class="c-table__badge--require">必須</span>
										</th>
										<td class="c-table__td">
											<div class="c-table__inputArea">
												<input @keyup="formChange()" v-model.lazy="password_confirmation" class="c-input"  id="password_confirmation" 
													name="password_confirmation" :type="inputTypePasswordConfirmation" :placeholder="placeholderPasswordConfirmation" 
													:style="{backgroundColor: inputColor( getErrorMessage(errors.password_confirmation) )}"
													onpaste="return false" autocomplete="on"> 
												<i v-if="inputTypePasswordConfirmation === 'password'" class="fa-regular fa-eye c-input__togglePasswordIcon" @click="changeTogglePasswordConfirmation"></i>
												<i v-else class="fa-regular fa-eye-slash c-input__togglePasswordIcon" @click="changeTogglePasswordConfirmation"></i>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.password_confirmation)">{{ getErrorMessage(errors.password_confirmation) }}</div>
											<div class="c-attention u-mt10">※ペーストして入力することはできません。</div>
										</td>
									</tr>
								</tbody>
							</table>
							
							<!-- 利用規約とプライバシーポリシーに同意した上で送信する -->
							<div class="c-form__alignCenterArea">
								<router-link :to="`/job20/rule`" class="c-form__link u-mr5" target="_blank" rel="noopener noreferrer">利用規約</router-link>と<router-link :to="`/job20/privacy`" class="c-form__link u-mr5" target="_blank" rel="noopener noreferrer">プライバシーポリシー</router-link>に<br class="u-tabShow">同意した上で登録する
							</div>

							<!-- 登録ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), createUser()" class="c-btn--m c-btn--subColor" 
										:class="[ {disabled: isDisabled}, {'c-btn--clicked': isClicked} ]" :disabled="isDisabled" value="会員登録する">
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
import FormChanged from '../mixin/FormChanged.vue';
import Loading from '../components/Loading.vue';
import Placeholder from '../mixin/Placeholder.vue';
import TogglePassword from '../mixin/TogglePassword.vue';
import Validation from '../mixin/Validation.vue';
import ValidationRegister from '../mixin/ValidationRegister.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"Loading": Loading,
	},

	mixins: [ Placeholder, FormChanged, TogglePassword, Validation, ValidationRegister ],

	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// タイトル
			title: '会員登録',
			// フォームデータ
			email: '',
			password: '',
			password_confirmation: '',
			// エラーオブジェクト
			errors: {
				email: [],
				password: [],
				password_confirmation: [],
			},
			// 処理中の画面表示
			isLoading: false,
		}
	},

	methods:{
		// ユーザー投稿処理
		createUser: async function(){
			// 処理中の画面表示
			this.isLoading = true;

			// ボタン連打を無効化
			this.isClicked = true;

			// エラー配列初期化
			this.errors = {};

			// Laravelの会員登録用のURL
			const url = '/job20/register';

			// 送信データを用意
			const params = {
				email: this.email,
				password: this.password,
				password_confirmation: this.password_confirmation
			};

			// axiosで使うthisを格納
			const self = this;

			// 非同期処理
			await axios.post(url, params)
			// 会員登録成功時の処理
			.then(function(response){
				// ローカルストレージにログイン情報とフラッシュメッセージを保存
				localStorage.setItem('isLogin', true)
				localStorage.setItem(['flash_message'],['会員登録が完了しました。']);

				// ログイン期限を2時間に設定
				const loginLimit = Date.now() + (60 * 60 * 2 * 1000);
				localStorage.setItem('loginLimit', loginLimit);

				// 検索ページへリダイレクト
				location.href = '/job20/search';

				// vuexのデータ削除
				self.$store.commit('Register/setEmail', '');
				self.$store.commit('Register/reset_errors_email', []);
			})
			// 会員登録失敗時の処理
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
		// vuexのデータ取得
		this.email = this.$store.state.Register.email;
		this.errors.email = this.$store.state.Register.errors.email;

		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 7);
	},
}
</script>