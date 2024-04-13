<template>
	<div>
		<!-- 退会確認のモーダル -->
		<transition name="modal">
			<Modal v-show="isShowWithdrawal">
				<template slot="body">
					<div class="c-modal__body">
						<p class="c-modal__text">退会します。よろしいですか？</p>
						<div class="c-modal__buttonArea" >
							<button class="c-modal__button" @click.prevent="withdrawal(), closeWithdrawalModal()">はい</button>
							<button class="c-modal__button--no" @click="closeWithdrawalModal()">いいえ</button>
						</div>
					</div>
				</template>
			</Modal>
		</transition>

		<!-- パンくずリスト -->
		<BreadCrumb :category="category" :title="title"></BreadCrumb>
	
		<div class="p-withdrawal c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-solid fa-gear"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<!-- バリデーションエラー時のアラート -->
				<ValidationError v-if="hasErrors && isSubmit" :errors="errors"></ValidationError>

				<div class="c-pageSet__navi">
					<div class="c-pageSet__catch--alert u-fs-l">
						<i class="fa-solid fa-circle-exclamation"></i>
						<span class="c-pageSet__catchText">退会されると、全ての登録情報が削除されます。</span>
					</div>
					<div class="c-pageSet__copy">退会手続き後は、登録情報を復活することができませんので、ご注意ください。<br>
						よろしければ、アンケートにご協力いただき、「<span class="u-fw-bold">退会する</span>」ボタンをクリックしてください。
					</div>
				</div>

				<section class="c-section">
					<h3 class="c-section__title--grad">退会の手続き</h3>
					<div class="c-section__container">
						
						<form class="c-form">
							<!-- csrf対策 -->
							<input type="hidden" name="_token" :value="csrf">

							<table class="c-table">
								<tbody>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">退会希望</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub p-withdrawal__tdRequest">
											<label class="c-label p-withdrawal__label" for="request">
												<input @change="formChange(), changeActiveCheck()" v-model="request" class="u-none" 
													type="checkbox" :value="request" name="request" id="request">
												<i v-show="isActiveCheck" class="fa-solid fa-check c-checkmark p-withdrawal__checkmark"></i>
												<span>当サイトの退会を希望します。</span>
											</label>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.request)">{{ getErrorMessage(errors.request) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--sub">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">退会理由</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<div class="c-selectBox  p-withdrawal__selectBox">
												<select @change="formChange()" v-model="reason" class="c-selectBox__select p-withdrawal__select" id="reason" name="reason"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.reason) )}">
													<option value v-if="isWidthS">選択してください</option>
													<option value v-else>退会理由を選択してください</option>
													<option v-for="item in reasons" :key="item.id" :value="item.name" :selected="reason == item.name">{{ item.name }}</option>
												</select>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.reason)">{{ getErrorMessage(errors.reason) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--sub u-alignTop">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">ご意見・ご要望</span>
												<span class="c-table__badge--any">任意</span>
											</div>
										</th>
										<td class="c-table__td--sub">
											<textarea @keyup="formChange()" ref="message"  v-model="withdrawal_message" class="c-input--textarea" 
												id="message" name="message" rows="10" placeholder="ご意見・ご要望を入力" autocomplete="on"
												:style="[ message_height, {backgroundColor: inputColor( getErrorMessage (errors.withdrawal_message) ) } ]"></textarea>
											<div class="c-table__countText">現在 <span :style="{color: inputLengthColor(withdrawal_message, 200)}">{{ inputLength(withdrawal_message) }}</span>文字 / 最大 200文字</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.withdrawal_message)">{{ getErrorMessage(errors.withdrawal_message) }}</div>
										</td>
									</tr>
								</tbody>
							</table>

							<!-- 退会ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), openWithdrawalModal()" class="c-btn--m" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClicked} ]" :value="'退会する'">
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
import HandleResize from '../mixin/HandleResize.vue';
import Loading from '../components/Loading.vue';
import Modal from '../components/Modal.vue';
import Validation from '../mixin/Validation.vue';
import ValidationError from '../components/ValidationError.vue';
import ValidationWithdrawal from '../mixin/ValidationWithdrawal.vue';
import WithdrawalSelectBox from '../mixin/WithdrawalSelectBox.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"Loading": Loading,
		"Modal": Modal,
		"ValidationError": ValidationError,
	},
	mixins: [ FormChanged, HandleResize, Validation, ValidationWithdrawal, WithdrawalSelectBox ],
	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// パンくずリストに渡すデータ
			title: '退会の手続き',
			category: {
				id: 5,
				name: '各種設定',
				path: '/job20/setting',
			},
			// エラーオブジェクト
			errors: {
				request: [],
				reason: [],
				withdrawal_message: [],
			},
			// 送信判定フラグ
			isSubmit: false,
			// 処理中の画面表示
			isLoading: false,
		}
	},

	methods:{
		// モーダル表示・非表示
		openWithdrawalModal(){
			this.$store.commit('Modal/open', { modalName: "withdrawal" });
		},
		closeWithdrawalModal() {
			this.$store.commit('Modal/close', { modalName : "withdrawal" });
		},

		// チェックマークの表示切り替え
		changeActiveCheck(){
			this.isActiveCheck = !this.isActiveCheck;
		},

		// テキストエリアの高さ変更
		resizeMessageArea(){
			this.$store.commit('Withdrawal/set_message_height', "auto");
			this.$nextTick(()=>{
				this.$store.commit('Withdrawal/set_message_height', this.$refs.message.scrollHeight + 'px');
			});
		},

		// 退会処理
		withdrawal: async function (){
			// 処理中の画面表示
			this.isLoading = true;

			// エラー配列初期化
			this.errors = {};

			// 送信データを用意
			const params = {
				request: this.request,
				reason: this.reason,
				withdrawal_message: this.withdrawal_message,
			};

			// axiosで使うthisを格納
			const self = this;

			await axios.post('/job20/web/withdrawal', params)
			.then(response => {
				if(response.data.successFlg){
					// ログアウト処理
					self.$router.push({ name: 'logout' });					
				}else{
					self.$router.push({ name: 'error' });
				}
			}).catch(error => {
				let errors = {};
				// 返却データを配列化
				for(let key in error.response.data.errors) {
					errors[key] = error.response.data.errors[key];
				}

				// これがないとリアルタイムバリデーションが作動しない
				// Laravelのバリデーションでエラーメッセージを格納した後、他のエラー配列が定義されていないため
				if(errors.request === undefined){
					errors.request = [];
				}
				if(errors.reason === undefined){
					errors.reason = [];
				}
				if(errors.withdrawal_message === undefined){
					errors.withdrawal_message = [];
				}

				// dataプロパティに代入
				self.errors = errors;

				// フォーム編集フラグは有効のままにする
				self.formChange();

				// 送信後にエラーがあればアラート表示
				self.isSubmit = true;

				// トップにスクロール
				window.scrollTo({top: 0, behavior: 'smooth'})
			});

			// ボタンを初期状態に戻す
			this.isDisabled = true;

			// 処理中の画面を非表示
			this.isLoading = false;
		},
	},

	computed:{
		computedCsrf() {
		},

		// モーダル表示判定フラグ
		isShowWithdrawal: function () {
			return this.$store.state.Modal.modal.withdrawal.isShow;
		},

		// テキストエリアの高さ返却
		message_height(){
			return {
				"height": this.$store.state.Withdrawal.message_height,
			}
		},

		// 入力データをバインディング
		form: {
			get() {
				return this.$store.state.Withdrawal.form;
			},
		},
		request: {
			get() {
				return this.$store.state.Withdrawal.form.request;
			},
			set(bool) {
				this.$store.commit('Withdrawal/setRequest', bool);
			}
		},
		reason: {
			get() {
				return this.$store.state.Withdrawal.form.reason;
			},
			set(str) {
				this.$store.commit('Withdrawal/setReason', str);
			}
		},
		withdrawal_message: {
			get() {
				return this.$store.state.Withdrawal.form.withdrawal_message;
			},
			set(str) {
				this.$store.commit('Withdrawal/setWithdrawalMessage', str);
			}
		},
		isActiveCheck: {
			get() {
				return this.$store.state.Withdrawal.isActiveCheck;
			},
			set(bool) {
				this.$store.commit('Withdrawal/setIsActiveCheck', bool);
			}
		},
	},

	mounted(){
		// 各項目が空の場合は、エラーメッセージ削除
		// watchが初回から有効だが、「入力必須です。」も削除する
		if(this.request === false){
			this.errors.request = [];
		}
		if(this.reason === ''){
			this.errors.reason = [];
		}

		this.handleResize();
		// 画面幅を取得するイベントを設定
		window.addEventListener('resize', this.handleResize);
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 5);
	},

	beforeDestroy: function () {
		window.removeEventListener('resize', this.handleResize)
	},
}
</script>
