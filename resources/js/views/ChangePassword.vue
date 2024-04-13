<template>
	<div>
		<!-- フラッシュメッセージ -->
		<FlashMessage></FlashMessage>

		<!-- パンくずリスト -->
		<BreadCrumb :category="category" :title="title"></BreadCrumb>
		
		<div class="p-changePassword c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-gear"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<div class="c-pageSet__navi">
					<div class="c-pageSet__catch">パスワードの変更ができます。</div>
					<div class="c-pageSet__copy">以下の項目に入力のうえ、ページ下の「<span class="u-fw-bold">変更する</span>」ボタンをクリックしてください。
						<div class="c-attention">※ アカウントの不正利用を防ぐため、他社サービスでご利用されているパスワードの使い回しはお控えください。</div>
					</div>
				</div>

				<section class="c-section">
					<h3 class="c-section__title--grad">パスワード</h3>
					<div class="c-section__container">
						
						<form class="c-form">
							<!-- csrf対策 -->
							<input type="hidden" name="_token" :value="csrf">

							<table class="c-table--thWidthL">
								<tbody>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">現在のパスワード</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<div class="c-table__inputArea--l">
												<input @keyup="formChange()" v-model.lazy="password_old" id="password_old" name="password_old" :type="inputTypePasswordOld" 
													placeholder="現在のパスワードを入力" class="c-input--l"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.password_old) )}">
												<i v-if="inputTypePasswordOld === 'password'" class="fa-regular fa-eye c-input__togglePasswordIcon" @click="changeTogglePasswordOld"></i>
												<i v-else class="fa-regular fa-eye-slash c-input__togglePasswordIcon" @click="changeTogglePasswordOld"></i>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.password_old)">{{ getErrorMessage(errors.password_old) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">新しいパスワード</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<div class="c-table__inputArea--l">
												<input @keyup="formChange()" v-model.lazy="password_new" id="password_new" name="password_new" :type="inputTypePassword" 
													placeholder="半角英数8-32文字で入力" class="c-input--l"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.password_new) )}">
												<i v-if="inputTypePassword === 'password'" class="fa-regular fa-eye c-input__togglePasswordIcon" @click="changeTogglePassword"></i>
												<i v-else class="fa-regular fa-eye-slash c-input__togglePasswordIcon" @click="changeTogglePassword"></i>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.password_new)">{{ getErrorMessage(errors.password_new) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">新しいパスワード(確認)</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<div class="c-table__inputArea--l">
												<input @keyup="formChange()" v-model.lazy="password_new_confirmation" id="password_new_confirmation" name="password_new_confirmation" :type="inputTypePasswordConfirmation" 
													placeholder="新しいパスワードを再入力" class="c-input--l" onpaste="return false" 
													:style="{backgroundColor: inputColor( getErrorMessage(errors.password_new_confirmation) )}">
												<i v-if="inputTypePasswordConfirmation === 'password'" class="fa-regular fa-eye c-input__togglePasswordIcon" @click="changeTogglePasswordConfirmation"></i>
												<i v-else class="fa-regular fa-eye-slash c-input__togglePasswordIcon" @click="changeTogglePasswordConfirmation"></i>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.password_new_confirmation)">{{ getErrorMessage(errors.password_new_confirmation) }}</div>
											<div class="c-attention">※ペーストして入力することはできません。</div>
										</td>
									</tr>
								</tbody>
							</table>

							<!-- パスワードリマインダー画面へのリンク -->
							<div class="c-form__alignCenterArea">
								※パスワードをお忘れの方は<router-link :to="`/job20/password_remind_send`" class="c-form__link u-ml5">こちら</router-link>
							</div>

							<!-- 変更ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), updatePassword()" class="c-btn--m" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClicked} ]" :disabled="isDisabled" :value="'変更する'">
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
import TogglePassword from '../mixin/TogglePassword.vue';
import Validation from '../mixin/Validation.vue';
import ValidationChangePassword from '../mixin/ValidationChangePassword.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"FlashMessage": FlashMessage,
		"Loading": Loading,
	},
	mixins: [ FormChanged, TogglePassword, Validation, ValidationChangePassword ],
	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// パンくずリストに渡すデータ
			title: 'パスワード変更',
			category: {
				id: 5,
				name: '各種設定の確認・変更',
				path: '/job20/setting',
			},
			// フォームデータ
			password_db: '',
			password_old: '',
			password_new: '',
			password_new_confirmation: '',
			// エラーオブジェクト
			errors: {
				password_old: [],
				password_new: [],
				password_new_confirmation: [],
			},
			isLoading: true,
			// 離脱アラートのタイプ
			alertType: 'send',
		}
	},

	methods: {
		// ユーザーのパスワード取得
		getPassword: async function (){
			// axiosで使うthisを格納
			const self = this;

			await axios.get('/job20/web/change_password')
			.then(response => {
				if(response.data.successFlg && response.data.password){
					self.password_db = response.data.password;
					self.isLoading = false;
				}else{
					self.$router.push({ name: 'error'})
				}
			}).catch(error => console.log(error) );
		},

		// パスワード変更処理
		updatePassword: async function (){
			// 処理中の画面表示
			this.isLoading = true;

			// ボタン連打を無効化
			this.isClicked = true;

			// エラー配列初期化
			this.errors = {};

			// axiosで使うthisを格納
			const self = this;

			await axios.post('/job20/web/change_password', {
				password_new: this.password_new,
				// testFlg = false,
			}).then(response => {
				if(response.data.successFlg) {
					self.$store.commit('Modal/setMessage', 'パスワードを変更しました。');
					self.$store.commit('Modal/setFlash', true);
					self.$router.push({ name: 'setting'})
				}else{
					self.$router.push({ name: 'error'})
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
		},
	},

	computed:{
		computedCsrf() {},
	},

	mounted(){
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 5);
		// ユーザーのパスワード取得
		this.getPassword();
	},
}
</script>
