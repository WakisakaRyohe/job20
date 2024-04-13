<template>
	<div>
		<!-- パンくずリスト -->
		<BreadCrumb :category="category" :title="title"></BreadCrumb>
		
		<div class="p-editSelfPromotion c-inner--l">
			<!-- ロード中 -->
			<Loading v-if="isLoading"></Loading>

			<div v-else class="c-pageSet">
				<!-- ページタイトル -->
				<div class="c-pageSet__title">
					<i class="fa-regular fa-file-lines"></i><h2 class="c-pageSet__titleText">{{ title }}</h2>
				</div>

				<!-- バリデーションエラー時のアラート -->
				<ValidationError v-if="hasErrors && isSubmit" :errors="errors"></ValidationError>

				<section class="c-section">
					<div class="c-section__container u-radius5">
						<form class="c-form">
							<!-- csrf対策 -->
							<input type="hidden" name="_token" :value="csrf">

							<h3 class="c-form__title--self_promotion">自己PR</h3>

							<table class="c-table">
								<tbody>
									<tr class="c-table__tr">
										<th class="c-table__th u-p20">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">自己PR</span>
												<span class="c-table__badge--any">任意</span>
											</div>
										</th>
										<td class="c-table__td">
											<textarea @keyup="formChange()" v-model="self_promotion" ref="self_promotion_area" class="c-input--textarea" id="self_promotion" 
												name="self_promotion" rows="10" placeholder="自己PRを入力してください" autocomplete="on"
												:style="[self_promotion_height, {backgroundColor: inputColor( getErrorMessage(errors.self_promotion) )} ]"></textarea>
											<div class="c-table__countText">現在 <span :style="{color: inputLengthColor(self_promotion, 800)}">{{ inputLength(self_promotion) }}</span>文字 / 最大 800文字
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.self_promotion)">{{ getErrorMessage(errors.self_promotion) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th u-p20">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">志望動機</span>
												<span class="c-table__badge--any">任意</span>
											</div>
										</th>
										<td class="c-table__td">
											<textarea @keyup="formChange()" v-model="motivation" ref="motivation_area" class="c-input--textarea" id="motivation" 
												name="motivation" rows="10" placeholder="志望動機を入力してください" autocomplete="on"
												:style="[motivation_height, {backgroundColor: inputColor( getErrorMessage(errors.motivation) )} ]"></textarea>
											<div class="c-table__countText">現在 <span :style="{color: inputLengthColor(motivation, 800)}">{{ inputLength(motivation) }}</span>文字 / 最大 800文字
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.motivation)">{{ getErrorMessage(errors.motivation) }}</div>
										</td>
									</tr>
								</tbody>
							</table>
										
							<!-- 登録ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), updateSelfPromotion()" class="c-btn--m" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClickedSelfPromotion} ]" :disabled="isDisabled" :value="btnText">
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
import CheckEdit from '../mixin/CheckEdit.vue';
import FormChanged from '../mixin/FormChanged.vue';
import Loading from '../components/Loading.vue';
import Validation from '../mixin/Validation.vue';
import ValidationError from '../components/ValidationError.vue';
import ValidationSelfPromotion from '../mixin/ValidationSelfPromotion.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"Loading": Loading,
		"ValidationError": ValidationError,
	},

	mixins: [ CheckEdit, FormChanged, Validation, ValidationSelfPromotion ],

	props: [
		'propSelfPromotion',
	],

	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// パンくずリストに渡すデータ
			title: '自己PRの登録・更新',
			category: {
				id: 4,
				name: 'WEB履歴書',
				path: '/job20/web_resume',
			},
			// エラーオブジェクト
			errors: {
				self_promotion: [],
				motivation: [],
			},
			// 送信判定フラグ
			isSubmit: false,
			// 初回にテキストエリアの高さ変更
			isResize: false,
		}
	},

	methods: {
		// テキストエリアの高さ変更
		resizeSelfPromotionArea(){	
			if(this.$refs.self_promotion_area){		
				this.$store.commit('EditSelfPromotion/set_self_promotion_height', "auto");
				this.$nextTick(()=>{
					this.$store.commit('EditSelfPromotion/set_self_promotion_height', this.$refs.self_promotion_area.scrollHeight + 'px');
				})
			}
		},
		resizeMotivationArea(){
			if(this.$refs.motivation_area){
				this.$store.commit('EditSelfPromotion/set_motivation_height', "auto");
				this.$nextTick(()=>{
					this.$store.commit('EditSelfPromotion/set_motivation_height', this.$refs.motivation_area.scrollHeight + 'px');
				})
			}
		},

		// 自己PR更新処理
		updateSelfPromotion: async function (){
			await this.$store.dispatch('EditSelfPromotion/updateSelfPromotion');

			// エラーがある場合vuexから代入して削除
			this.errors = this.$store.state.EditSelfPromotion.errors;
			this.$store.commit('EditSelfPromotion/setErrors', {});

			// 送信後にエラーがあればアラート表示
			this.isSubmit = true;

			// ボタンを初期状態に戻す
			this.isDisabled = true;
		},

		// 自己PRデータ取得
		getSelfPromotion: async function () {
			// WEB履歴書ページから線した場合
			if(this.propSelfPromotion){
				// console.log('propsあり');
				this.$store.commit('EditSelfPromotion/set_data', this.propSelfPromotion);

				// 入力されていない任意項目に空文字をセット(nullのままだとDBに文字列の「null」が登録される)
				if(this.propSelfPromotion.self_promotion === null){
					this.$store.commit('EditSelfPromotion/set_self_promotion', '');
				}
				if(this.propSelfPromotion.motivation === null){
					this.$store.commit('EditSelfPromotion/set_motivation', '');
				}

				// 編集画面と判定する
				this.editFlg = true;

				// ロード画面非表示
				this.$store.commit('EditSelfPromotion/setIsLoading', false);

			// 再読み込みやURL直打ちの場合
			}else{
				// console.log('propsなし');

				// axiosで使うthisを格納
				const self = this;

				await axios.get('/job20/web/self_promotion')
				.then(response => {
					if(response.data.successFlg){
						if(response.data.self_promotion){
							self.$store.commit('EditSelfPromotion/set_data', response.data.self_promotion);

							// 入力されていない任意項目に空文字をセット(nullのままだとDBに文字列の「null」が登録される)
							if(response.data.self_promotion.self_promotion === null){
								self.$store.commit('EditSelfPromotion/set_self_promotion', '');
							}
							if(response.data.self_promotion.motivation === null){
								self.$store.commit('EditSelfPromotion/set_motivation', '');
							}

							// 編集画面か判定
							if(response.data.editFlg){
								// console.log('編集画面');
								this.editFlg = true;
							}
						}else{
							// console.log('登録画面');
						}

					}else{
						self.$router.push({ name: 'error' });
					}

				}).catch(error => {
					console.log(error);
					self.$router.push({ name: 'error' });
				});

				// ロード画面非表示
				self.$store.commit('EditSelfPromotion/setIsLoading', false);
			}
		},
	},

	computed: {
		// csrf対策
		computedCsrf() {
		},

		// テキストエリアの高さ返却
		self_promotion_height(){
			return {
				"height": this.$store.state.EditSelfPromotion.self_promotion_height,
			}
		},
		motivation_height(){
			return {
				"height": this.$store.state.EditSelfPromotion.motivation_height,
			}
		},

		// 入力データをバインディング
		self_promotion_data: {
			get() {
				return this.$store.state.EditSelfPromotion.self_promotion;
			},
		},
		self_promotion: {
			get() {
				return this.$store.state.EditSelfPromotion.self_promotion.self_promotion;
			},
			set(str) {
				this.$store.commit('EditSelfPromotion/set_self_promotion', str);
			}
		},
		motivation: {
			get() {
				return this.$store.state.EditSelfPromotion.self_promotion.motivation;
			},
			set(str) {
				this.$store.commit('EditSelfPromotion/set_motivation', str);
			}
		},
		// ボタン連打を無効化
		isClickedSelfPromotion: {
			get() {
				return this.$store.state.EditSelfPromotion.isClicked;
			},
		},
		// 処理中の画面表示
		isLoading: {
			get() {
				return this.$store.state.EditSelfPromotion.isLoading;
			},
		},
	},

	mounted(){
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 4);
		// 自己PRデータ取得
		this.getSelfPromotion();
	},

	updated(){
		if(!this.isResize){
			// テキストエリアの高さ変更
			this.resizeSelfPromotionArea();
			this.resizeMotivationArea();
			this.isResize = true;
		}
	}
}
</script>
