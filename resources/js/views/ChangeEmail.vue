<template>
	<div>
		<!-- パンくずリスト -->
		<BreadCrumb :category="category" :title="title"></BreadCrumb>
		
		<div class="p-changeEmail c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-envelope"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<div class="c-pageSet__navi">
					<div class="c-pageSet__catch">メールアドレスの変更には、メール認証が必要です。</div>
					<div class="c-pageSet__copy">
						新しいメールアドレスを入力して、「<span class="u-fw-bold">認証メールの送信</span>」ボタンを押してください。
						<div class="c-attention">※ 認証メール記載のURLをクリックすると、メールアドレス変更が完了します。</div>
					</div>
				</div>

				<section class="c-section">
					<h3 class="c-section__title--grad">メールアドレス</h3>
					<div class="c-section__container">
						
						<form class="c-form">
							<!-- csrf対策 -->
							<input type="hidden" name="_token" :value="csrf">

							<table class="c-table--thWidthM">
								<tbody>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">メールアドレス</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<input @keyup="formChange()" v-model.lazy="email_new" id="email_new" name="email_new" type="email" 
												placeholder="新しいメールアドレスを入力" class="c-input--l"
												:style="{backgroundColor: inputColor( getErrorMessage(errors.email_new) )}">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.email_new)">{{ getErrorMessage(errors.email_new) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">メールアドレス(再入力)</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<input @keyup="formChange()" v-model.lazy="email_confirmation" id="email_confirmation" name="email_confirmation" type="email" 
												placeholder="新しいメールアドレスを再入力" class="c-input--l"
												:style="{backgroundColor: inputColor( getErrorMessage(errors.email_confirmation) )}">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.email_confirmation)">{{ getErrorMessage(errors.email_confirmation) }}</div>
										</td>
									</tr>
								</tbody>
							</table>

							<!-- 変更ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), updateEmail()" class="c-btn--m" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClicked} ]" :disabled="isDisabled" :value="'認証メール送信'">
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
import Validation from '../mixin/Validation.vue';
import ValidationChangeEmail from '../mixin/ValidationChangeEmail.vue';

export default {
	components: {
		"Loading": Loading,
		"BreadCrumb": BreadCrumb,
	},
	mixins: [ FormChanged, Validation, ValidationChangeEmail ],
	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// パンくずリストに渡すデータ
			title: 'メールアドレス変更',
			category: {
				id: 5,
				name: '各種設定の確認・変更',
				path: '/job20/setting',
			},
			// エラーオブジェクト
			errors: {
				email_new: [],
				email_confirmation: [],
			},
			// 処理中の画面表示
			isLoading: true,
			// 離脱アラートのタイプ
			alertType: 'send',
		}
	},

	methods: {
		// ユーザー情報を取得
		getUser: async function (){
			// axiosで使うthisを格納
			const self = this;

			await axios.get('/job20/web/change_email')
			.then(response => {
				if(response.data.email){
					self.$store.commit('ChangeEmail/setEmailOld', response.data.email);
					self.isLoading = false;
				}else{
					self.$router.push({ name: 'error'})
				}
			}).catch(error => console.log(error) );
		},

		// メールアドレス認証メール送信処理
		updateEmail: async function (){
			// 処理中の画面表示
			this.isLoading = true;

			// ボタン連打を無効化
			this.isClicked = true;

			// エラー配列初期化
			this.errors = {};

			// axiosで使うthisを格納
			const self = this;

			await axios.post('/job20/web/change_email', {
				email_new: self.email_new,
				// testFlg = false,
			}).then(response => {
				if(response.data.session) {
					self.$store.commit('Modal/setMessage', 'メールアドレス認証メールを送信しました。');
				}else{
					self.$store.commit('Modal/setMessage', 'メールアドレス認証メールの送信に失敗しました。<br>もう１度やり直してください。');
				}
				self.$store.commit('Modal/setFlash', true);
				self.$router.push({ name: 'setting'})
			})
			.catch(error => {
				let errors = {};
				// 返却データを配列化
				for(let key in error.response.data.errors) {
					errors[key] = error.response.data.errors[key];
				}

				// これがないと、リアルタイムバリデーションが作動しない
				if(errors.email_confirmation === undefined){
					errors.email_confirmation = [];
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

		// 入力データをバインディング
		form: {
			get() {
				return this.$store.state.ChangeEmail.form;
			},
		},
		email_old: {
			get() {
				return this.$store.state.ChangeEmail.form.email_old;
			},
			set(str) {
				this.$store.commit('ChangeEmail/setEmailOld', str);
			}
		},
		email_new: {
			get() {
				return this.$store.state.ChangeEmail.form.email_new;
			},
			set(str) {
				this.$store.commit('ChangeEmail/setEmailNew', str);
			}
		},
		email_confirmation: {
			get() {
				return this.$store.state.ChangeEmail.form.email_confirmation;
			},
			set(str) {
				this.$store.commit('ChangeEmail/setEmailConfirmation', str);
			}
		},
	},

	mounted(){
		// 各項目が空の場合は、エラーメッセージ削除
		// watchが初回から有効だが、「入力必須です。」も削除する
		if(this.email_new === ''){
			this.errors.email_new = [];
		}
		if(this.email_confirmation === ''){
			this.errors.email_confirmation = [];
		}

		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 5);

		// ユーザー情報を取得
		this.getUser();
	},

	destroyed(){
		this.$store.commit('ChangeEmail/setEmailOld', '');
	}
}
</script>