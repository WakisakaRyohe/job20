<template>
	<div>
		<!-- フラッシュメッセージ -->
		<FlashMessage></FlashMessage>

		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="title"></BreadCrumb>

		<div class="p-contact c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-envelope"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<section class="c-section">
					<h3 class="c-section__title--grad">お問い合わせフォーム</h3>
					<div class="c-section__container u-radius5">
						<div class="c-section__description">
							通常、3営業日以内に追って弊社よりご連絡差し上げます。
							<div class="c-attention">※誠に勝手ながら、土・日・祝日はお休みをいただいております。</div>
						</div>

						<form class="c-form">
							<!-- csrf対策 -->
							<input type="hidden" name="_token" :value="csrf">

							<table class="c-table">
								<tbody>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">お名前</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<input @keyup="formChange()" v-model.lazy.trim="name" id="name" name="name" type="text" 
												placeholder="お名前を入力" class="c-input--l"
												:style="{backgroundColor: inputColor( getErrorMessage(errors.name) )}" autocomplete="on">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.name)">{{ getErrorMessage(errors.name) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">メールアドレス</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<input @keyup="formChange()" v-model.lazy.trim="email" id="email" name="email" type="email" 
												placeholder="メールアドレスを入力" class="c-input--l"
												:style="{backgroundColor: inputColor( getErrorMessage(errors.email) )}" autocomplete="on">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.email)">{{ getErrorMessage(errors.email) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">お問い合わせの種類</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<div class="c-selectBox--m">
												<select @change="formChange()" v-model.number="type" class="c-selectBox__select" id="type" name="type"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.type) )}">
													<option value="0" v-if="isShow">お問い合わせの種類を選択</option>
													<option value="0" v-else>選択してください</option>
													<option v-for="item in types" :key="item.id" :value="item.id" :selected="type == item.id">{{ item.name }}</option>
												</select>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.type)">{{ getErrorMessage(errors.type) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">件名</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<input @keyup="formChange()" v-model.lazy.trim="subject" id="subject" name="subject" type="text" 
												placeholder="件名を入力" class="c-input--l"
												:style="{backgroundColor: inputColor( getErrorMessage(errors.subject) )}" autocomplete="on">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.subject)">{{ getErrorMessage(errors.subject) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--sub u-alignTop">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">お問い合わせ内容</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<textarea @keyup="formChange()" ref="message" 
												v-model="message" class="c-input--textarea" id="message" 
												name="message" rows="10" placeholder="お問い合わせ内容を入力" autocomplete="on"
												:style="[ message_height, {backgroundColor: inputColor( getErrorMessage (errors.message) ) } ]"></textarea>
											<div class="c-table__countText">現在 <span :style="{color: inputLengthColor(message, 500)}">{{ inputLength(message) }}</span>文字 / 最大 500文字</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.message)">{{ getErrorMessage(errors.message) }}</div>
										</td>
									</tr>
								</tbody>
							</table>

							<!-- プライバシーポリシー画面へのリンク -->
							<div class="c-form__alignCenterArea">
								<router-link :to="`/job20/privacy`" class="c-form__link u-mr5" target="_blank" rel="noopener noreferrer">プライバシーポリシー</router-link>に<br class="u-spShow">同意した上で送信する
								<div class="c-attention">※ 「mail@wakisakaryohei.com」より、入力されたメールアドレスに確認メールを送信します。</div>
							</div>

							<!-- 送信ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), contact()" class="c-btn--m" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClicked} ]" :disabled="isDisabled" :value="'送信する'">
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
import ValidationContact from '../mixin/ValidationContact.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"FlashMessage": FlashMessage,
		"Loading": Loading,
		"Modal": Modal,
	},
	mixins: [ FormChanged, Validation, ValidationContact ],
	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('type'),
			title: 'お問い合わせ',
			// 画面幅を判定
			isShow: true,
			// 離脱アラートのタイプ
			alertType: 'send',
			// エラーオブジェクト
			errors: {
				name: [],
				email: [],
				type: [],
				subject: [],
				message: [],
			},
			// お問い合わせの種類
			types: [
				{'id' : 1, 'name' : 'ID・パスワード、ログインについて'},
				{'id' : 2, 'name' : 'サイトの利用方法について'},
				{'id' : 3, 'name' : 'WEB履歴書について'},
				{'id' : 4, 'name' : '求人検索について'},
				{'id' : 5, 'name' : '応募について'},
				{'id' : 6, 'name' : '退会について'},
				{'id' : 7, 'name' : '求人情報掲載について'},
				{'id' : 8, 'name' : 'その他'},
			],
			isLoading: false,
		}
	},

	methods:{
		// 画面幅を検知するイベント
		handleResize: function() {
			const width = window.innerWidth;
			(width < 414) ? this.isShow = false : this.isShow = true;
		},

		// テキストエリアの高さ変更
		resizeMessageArea(){
			if(this.$refs.message){
				this.$store.commit('Contact/set_message_height', "auto");
				this.$nextTick(()=>{
					this.$store.commit('Contact/set_message_height', this.$refs.message.scrollHeight + 'px');
				});
			}
		},

		// 送信処理
		contact: async function (){
			// 処理中の画面表示
			this.isLoading = true;

			// ボタン連打を無効化
			this.isClicked = true;

			// エラー配列初期化
			this.errors = {};

			// 送信データを用意
			const params = {
				name: this.name,
				email: this.email,
				type: this.type,
				subject: this.subject,
				message: this.message,
			};

			// axiosで使うthisを格納
			const self = this;

			await axios.post('/job20/web/contact', params)
			.then(response => {
				if(response.data.successFlg){
					// ローカルストレージにフラッシュメッセージを保存
					localStorage.setItem(['flash_message'],['お問い合わせを受け付けました']);
								
					// リロードしてフラッシュメッセージ表示
					location.reload();
					// location.href = '/job20/contact';

					// vuexのデータ初期化
					self.$store.commit('Contact/setName', null);
					self.$store.commit('Contact/setEmail', null);
					self.$store.commit('Contact/setType', 0);
					self.$store.commit('Contact/setSubject', null);
					self.$store.commit('Contact/setMessage', null);
					// 送信済み判定フラグ変更
					self.$store.commit('Contact/setIsSubmit', true);

				}else{
					self.$router.push({ name: 'error' });
				}
			})

			// 失敗時の処理
			.catch(error => {
				let errors = {};
				// 返却データを配列化
				for(let key in error.response.data.errors) {
					errors[key] = error.response.data.errors[key];
				}

				// これがないと、パスワード入力でリアルタイムバリデーションが作動しない
				// Laravelのバリデーションでエラーメッセージを格納した後、他のエラー配列が定義されていないため
				if(errors.name === undefined){
					errors.name = [];
				}
				if(errors.type === undefined){
					errors.type = [];
				}
				if(errors.subject === undefined){
					errors.subject = [];
				}
				if(errors.message === undefined){
					errors.message = [];
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
		// csrf対策
		computedCsrf() {
		},

		// テキストエリアの高さ返却
		message_height(){
			return {
				"height": this.$store.state.Contact.message_height,
			}
		},

		// 入力データをバインディング
		form: {
			get() {
				return this.$store.state.Contact.form;
			},
		},
		name: {
			get() {
				return this.$store.state.Contact.form.name;
			},
			set(str) {
				this.$store.commit('Contact/setName', str);
			}
		},
		email: {
			get() {
				return this.$store.state.Contact.form.email;
			},
			set(str) {
				this.$store.commit('Contact/setEmail', str);
			}
		},
		type: {
			get() {
				return this.$store.state.Contact.form.type;
			},
			set(str) {
				this.$store.commit('Contact/setType', str);
			}
		},
		subject: {
			get() {
				return this.$store.state.Contact.form.subject;
			},
			set(str) {
				this.$store.commit('Contact/setSubject', str);
			}
		},
		message: {
			get() {
				return this.$store.state.Contact.form.message;
			},
			set(str) {
				this.$store.commit('Contact/setMessage', str);
			}
		},
		isSubmit: {
			get() {
				return this.$store.state.Contact.isSubmit;
			},
		},
	},

	mounted(){
		// 各項目が空の場合は、エラーメッセージ削除
		// watchが初回から有効だが、「入力必須です。」も削除する
		if(this.name === ''){
			this.errors.name = [];
		}
		if(this.email === ''){
			this.errors.email = [];
		}
		if(this.type === 0){
			this.errors.type = [];
		}
		if(this.subject === ''){
			this.errors.subject = [];
		}
		if(this.message === ''){
			this.errors.message = [];
		}

		// 画面幅を取得するイベントを設定
		this.handleResize();
		window.addEventListener('resize', this.handleResize);
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 0);
	},

	beforeDestroy: function () {
		window.removeEventListener('resize', this.handleResize)
	},
}
</script>
