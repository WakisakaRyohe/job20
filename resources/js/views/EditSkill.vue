<template>
	<div>
		<SearchModal
			type="typeCertification"
			title="保有資格を選択してください。"
			:isFreeword="true"
		></SearchModal>

		<!-- パンくずリスト -->
		<BreadCrumb :category="category" :title="title"></BreadCrumb>
	
		<div class="p-editSkill c-inner--l">
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

							<h3 class="c-form__title--skill">資格・スキル</h3>

							<table class="c-table">
								<tbody>
									<tr class="c-table__tr">
										<th class="c-table__th u-p20">
											<span class="c-table__thTitle">保有資格</span>
											<span class="c-table__badge--any">任意</span>
										</th>
										<td class="c-table__td">
											<div class="p-editSkill__driverLicence">
												<span>普通自動車免許（第一種）</span>
												<label class="c-label" for="普通自動車免許（第一種）">
													<input v-model="certifications" class="u-none" type="checkbox" 
														value="普通自動車免許（第一種）" name="普通自動車免許（第一種）" id="普通自動車免許（第一種）" 
														@change="formChange(), checkItemsCategory('typeCertification', 0, '普通自動車免許（第一種）'),
														changeActiveCheckArray('typeCertification', '普通自動車免許（第一種）')">
													<i v-if="isActiveCheck('typeCertification', '普通自動車免許（第一種）')" class="fa-solid fa-check c-checkmark"></i>あり
												</label>
											</div>
											<button type="button" class="c-selectBtn" 
												@click.prevent="open('typeCertification')"><span v-if="isWidthS">資格を選択</span><span v-else>保有資格を選択する</span></button>
											<ul v-if="certifications.length > 0" class="c-selectList">
												<li v-for="certification in certifications" class="c-selectList__item" :key="certification">
													<span class="c-selectList__label" 
														@click="formChange(), deleteCheck('typeCertification', certification), 
														checkItemsCategory('typeCertification', 0, certification),
														changeActiveCheckArray('typeCertification', certification)">
														<i class="fa-solid fa-xmark c-selectList__icon"></i><span class="c-selectList__text">{{ certification }}</span>
													</span>
												</li>
											</ul>
											<div class="c-attention u-mt10">※ 登録できる資格は最大20個です。</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.certifications)">{{ getErrorMessage(errors.certifications) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--p25">
											<span class="c-table__thTitle">英語スキル</span>
											<span class="c-table__badge--any">任意</span>
										</th>
										<td class="c-table__td">
											<dl class="c-dl">
												<dt class="c-dl__dt">会話</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--l">
														<select @change="formChange()" v-model="speaking_english_level" class="c-selectBox__select" id="speaking_english_level" name="speaking_english_level"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.speaking_english_level) )}">
															<option value v-if="isWidthS">会話レベルを選択</option>
															<option value v-else>会話レベルを選択してください</option>
															<option v-for="speaking_level in speaking_levels" :key="speaking_level.id" :value="speaking_level.name" :selected="speaking_english_level == speaking_level.name">{{ speaking_level.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg" v-if="getErrorMessage(errors.speaking_english_level)">{{ getErrorMessage(errors.speaking_english_level) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">読解</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--l">
														<select @change="formChange()" v-model="reading_english_level" class="c-selectBox__select" id="reading_english_level" name="reading_english_level"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.reading_english_level) )}">
															<option value v-if="isWidthS">読解レベルを選択</option>
															<option value v-else>読解レベルを選択してください</option>
															<option v-for="reading_level in reading_levels" :key="reading_level.id" :value="reading_level.name" :selected="reading_english_level == reading_level.name">{{ reading_level.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg" v-if="getErrorMessage(errors.reading_english_level)">{{ getErrorMessage(errors.reading_english_level) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">作文</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--l">
													<select @change="formChange()" v-model="writing_english_level" class="c-selectBox__select" id="writing_english_level" name="writing_english_level"
														:style="{backgroundColor: inputColor( getErrorMessage(errors.writing_english_level) )}">
														<option value v-if="isWidthS">作文レベルを選択</option>
														<option value v-else>作文レベルを選択してください</option>
														<option v-for="writing_level in writing_levels" :key="writing_level.id" :value="writing_level.name" :selected="writing_english_level == writing_level.name">{{ writing_level.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg" v-if="getErrorMessage(errors.writing_english_level)">{{ getErrorMessage(errors.writing_english_level) }}</div>
												</dd>
											</dl>
											<div class="c-flexInput p-editSkill__score">
												<div class="p-editSkill__toeic">
													<span class="p-editSkill__scoreTitle">TOEIC</span>
													<input @keyup="formChange()" v-model.lazy="toeic_score" class="c-input--s p-editSkill__scoreInput--toeic" id="toeic_score" 
													name="toeic_score" type="text" placeholder="" autocomplete="on"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.toeic_score) )}">点
												</div>
												<div class="p-editSkill__toefl">
													<span class="p-editSkill__scoreTitle">TOEFL</span>
													<input @keyup="formChange()" v-model.lazy="toefl_score" class="c-input--s p-editSkill__scoreInput" id="toeic_score_score" 
													name="toefl_score" type="text" placeholder="" autocomplete="on"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.toefl_score) )}">点
												</div>
											</div>
											<div class="c-table__errMsg p-editSkill__errMsgScore" v-show="CheckErrorMessageScore">{{ getErrorMessage(errors.toeic_score) }} <br v-show="getErrorMessage(errors.toeic_score)"> {{ getErrorMessage(errors.toefl_score) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--p25">
											<span class="c-table__thTitle">その他語学スキル</span>
											<span class="c-table__badge--any">任意</span>
										</th>
										<td class="c-table__td">
											<dl class="c-dl">
												<dt class="c-dl__dt">言語</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--s">
														<select @change="formChange()" v-model="others_language" class="c-selectBox__select" id="others_language" name="others_language"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.others_language) )}">
															<option value>言語を選択</option>
															<option v-for="others_language in others_languages" :key="others_language.id" :value="others_language.name" :selected="others_language == others_language.name">{{ others_language.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.others_language)">{{ getErrorMessage(errors.others_language) }}</div>
												</dd>
											</dl>
											<dl class="c-dl" v-if="isSelectOthersLanguage">
												<dt class="c-dl__dt"></dt>
												<dd class="c-dl__dd">
													<input @keyup="formChange()" v-model.lazy="input_others_language" class="c-input--s" id="input_others_language" 
														name="input_others_language" type="text" placeholder="言語名を入力" autocomplete="on"
														:style="{backgroundColor: inputColor( getErrorMessage(errors.input_others_language) )}">
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.input_others_language)">{{ getErrorMessage(errors.input_others_language) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">会話</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--l">
														<select @change="formChange()" v-model="speaking_others_language_level" class="c-selectBox__select" id="speaking_others_language_level" name="speaking_others_language_level"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.speaking_others_language_level) )}">
															<option value v-if="isWidthS">会話レベルを選択</option>
															<option value v-else>会話レベルを選択してください</option>
															<option v-for="speaking_level in speaking_levels" :key="speaking_level.id" :value="speaking_level.name" :selected="speaking_others_language_level == speaking_level.name">{{ speaking_level.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.speaking_others_language_level)">{{ getErrorMessage(errors.speaking_others_language_level) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">読解</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--l">
														<select @change="formChange()" v-model="reading_others_language_level" class="c-selectBox__select" id="reading_others_language_level" name="reading_others_language_level"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.reading_others_language_level) )}">
															<option value v-if="isWidthS">読解レベルを選択</option>
															<option value v-else>読解レベルを選択してください</option>
															<option v-for="reading_level in reading_levels" :key="reading_level.id" :value="reading_level.name" :selected="reading_others_language_level == reading_level.name">{{ reading_level.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.reading_others_language_level)">{{ getErrorMessage(errors.reading_others_language_level) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">作文</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--l">
														<select @change="formChange()" v-model="writing_others_language_level" class="c-selectBox__select" id="writing_others_language_level" name="writing_others_language_level"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.writing_others_language_level) )}">
															<option value v-if="isWidthS">作文レベルを選択</option>
															<option value v-else>作文レベルを選択してください</option>
															<option v-for="writing_level in writing_levels" :key="writing_level.id" :value="writing_level.name" :selected="writing_others_language_level == writing_level.name">{{ writing_level.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg" v-if="getErrorMessage(errors.writing_others_language_level)">{{ getErrorMessage(errors.writing_others_language_level) }}</div>
												</dd>
											</dl>
										</td>
									</tr>
								</tbody>
							</table>
										
							<!-- 登録ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), updateSkill()" class="c-btn--m" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClickedSkill} ]" :disabled="isDisabled" :value="btnText">
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
import HandleResize from '../mixin/HandleResize.vue';
import Loading from '../components/Loading.vue';
import SkillSelectBox from '../mixin/SkillSelectBox.vue';
import SearchModal from '../components/SearchModal.vue';
import SearchModalMixin from '../mixin/SearchModalMixin.vue';
import Validation from '../mixin/Validation.vue';
import ValidationError from '../components/ValidationError.vue';
import ValidationSkill from '../mixin/ValidationSkill.vue';

export default {
	components: {
		"Loading": Loading,
		"BreadCrumb": BreadCrumb,
		"SearchModal": SearchModal,
		"ValidationError": ValidationError,
	},
	mixins: [ CheckEdit, FormChanged, HandleResize, SearchModalMixin,  SkillSelectBox, Validation, ValidationSkill ],
	props: [
		'propSkill',
	],
	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// パンくずリストに渡すデータ
			title: '資格・スキルの登録・更新',
			category: {
				id: 4,
				name: 'WEB履歴書',
				path: '/job20/web_resume',
			},
			// エラーオブジェクト
			errors: {
				// 保有資格
				certifications: [],
				// 英語スキル
				reading_english_level: [],
				speaking_english_level: [],
				writing_english_level: [],
				toeic_score: [],
				toefl_score: [],
				// その他の語学スキル
				others_language: [],
				input_others_language: [],
				reading_others_language_level: [],
				speaking_others_language_level: [],
				writing_others_language_level: [],
			},
			// 送信判定フラグ
			isSubmit: false,
		}
	},

	methods: {				
		// 資格・スキル更新処理
		updateSkill: async function (){
			await this.$store.dispatch('EditSkill/updateSkill');

			// エラーがある場合vuexから代入して削除
			this.errors = this.$store.state.EditSkill.errors;
			this.$store.commit('EditSkill/setErrors', {});

			// 送信後にエラーがあればアラート表示
			this.isSubmit = true;

			// ボタンを初期状態に戻す
			this.isDisabled = true;
		},

		// 資格・スキルデータ取得
		getSkill: async function () {
			// WEB履歴書ページから遷移した場合
			if(this.propSkill){
				// console.log('propsあり');

				// vuexのデータ削除
				// console.log('vuexのデータ削除');
				this.$store.commit('EditSkill/set_skill', {});
				this.$store.commit('EditSkill/setCategories', []);
				this.$store.commit('EditSkill/setCategoryNum', 1);
				this.$store.commit('EditSkill/setSuggestList', []);
				this.$store.commit('EditSkill/setActiveCheckArray', []);
				this.$store.commit('EditSkill/setActiveTab', 1);
				this.$store.commit('EditSkill/setFreeword', '');
				this.$store.commit('EditSkill/setNoIndex', false);
				this.$store.commit('EditSkill/setInputFreeword', '');

				// vuexのデータ更新
				this.$store.commit('EditSkill/set_skill', this.propSkill);
				
				// 資格の配列がある場合、ループ処理
				if(this.propSkill.certifications){
					this.propSkill.certifications.forEach(certification => {
						// チェックつけたアイテムが所属するカテゴリを確認
						this.checkItemsCategory('typeCertification', 0, certification);
						// チェックマークをつけるアイテムの配列を変更
						this.changeActiveCheckArray('typeCertification', certification);
					});
				}

				// 入力されていない任意項目に空文字をセット(nullのままだとDBに文字列の「null」が登録される)
				if(this.propSkill.certifications === null || this.propSkill.certifications === ''){
					this.$store.commit('EditSkill/set_certifications_all', []);
				}
				if(this.propSkill.reading_english_level === null){
					this.$store.commit('EditSkill/set_reading_english_level', '');
				}
				if(this.propSkill.speaking_english_level === null){
					this.$store.commit('EditSkill/set_speaking_english_level', '');
				}
				if(this.propSkill.writing_english_level === null){
					this.$store.commit('EditSkill/set_writing_english_level', '');
				}
				if(this.propSkill.toeic_score === null){
					this.$store.commit('EditSkill/set_writing_english_level', '');
				}
				if(this.propSkill.toefl_score === null){
					this.$store.commit('EditSkill/set_toefl_score', '');
				}
				if(this.propSkill.others_language === null){
					this.$store.commit('EditSkill/set_others_language', '');
				}
				if(this.propSkill.input_others_language === null){
					this.$store.commit('EditSkill/set_input_others_language', '');
				}
				if(this.propSkill.reading_others_language_level === null){
					this.$store.commit('EditSkill/set_reading_others_language_level', '');
				}
				if(this.propSkill.speaking_others_language_level === null){
					this.$store.commit('EditSkill/set_speaking_others_language_level', '');
				}
				if(this.propSkill.writing_others_language_level === null){
					this.$store.commit('EditSkill/set_writing_others_language_level', '');
				}

				// 編集画面と判定する
				this.editFlg = true;

				// ロード画面非表示
				this.$store.commit('EditSkill/setIsLoading', false);

			// 再読み込みやURL直打ちの場合
			}else{
				// console.log('propsなし');

				// axiosで使うthisを格納
				const self = this;

				await axios.get('/job20/web/skill')
				.then(response => {
					if(response.data.successFlg){
						if(response.data.skill){
							// 編集画面か判定
							if(response.data.editFlg){
								// console.log('編集画面');
								this.editFlg = true;

								// vuexのデータ削除
								// console.log('vuexのデータ削除');
								this.$store.commit('EditSkill/set_skill', {});
								this.$store.commit('EditSkill/setCategories', []);
								this.$store.commit('EditSkill/setCategoryNum', 1);
								this.$store.commit('EditSkill/setSuggestList', []);
								this.$store.commit('EditSkill/setActiveCheckArray', []);
								this.$store.commit('EditSkill/setActiveTab', 1);
								this.$store.commit('EditSkill/setFreeword', '');
								this.$store.commit('EditSkill/setNoIndex', false);
								this.$store.commit('EditSkill/setInputFreeword', '');
							}

							// vuexのデータ更新
							this.$store.commit('EditSkill/set_skill', response.data.skill);

							// 資格の配列がある場合、ループ処理
							if(response.data.skill.certifications){
								response.data.skill.certifications.forEach(certification => {
									// チェックつけたアイテムが所属するカテゴリを確認
									this.checkItemsCategory('typeCertification', 0, certification);
									// チェックマークをつけるアイテムの配列を変更
									this.changeActiveCheckArray('typeCertification', certification);
								});
							}
											
							// 入力されていない任意項目に空文字をセット(nullのままだとDBに文字列の「null」が登録される)
							if(response.data.skill.certifications === null || response.data.skill.certifications === ''){
								this.$store.commit('EditSkill/set_certifications_all', []);
							}
							if(response.data.skill.reading_english_level === null){
								this.$store.commit('EditSkill/set_reading_english_level', '');
							}
							if(response.data.skill.speaking_english_level === null){
								this.$store.commit('EditSkill/set_speaking_english_level', '');
							}
							if(response.data.skill.writing_english_level === null){
								this.$store.commit('EditSkill/set_writing_english_level', '');
							}
							if(response.data.skill.toeic_score === null){
								this.$store.commit('EditSkill/set_toeic_score', '');
							}
							if(response.data.skill.toefl_score === null){
								this.$store.commit('EditSkill/set_toefl_score', '');
							}
							if(response.data.skill.others_language === null){
								this.$store.commit('EditSkill/set_others_language', '');
							}
							if(response.data.skill.input_others_language === null){
								this.$store.commit('EditSkill/set_input_others_language', '');
							}
							if(response.data.skill.reading_others_language_level === null){
								this.$store.commit('EditSkill/set_reading_others_language_level', '');
							}
							if(response.data.skill.speaking_others_language_level === null){
								this.$store.commit('EditSkill/set_speaking_others_language_level', '');
							}
							if(response.data.skill.writing_others_language_level === null){
								this.$store.commit('EditSkill/set_writing_others_language_level', '');
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
				self.$store.commit('EditSkill/setIsLoading', false);
			}
		},
	},

	computed: {
		// csrf対策
		computedCsrf() {
		},

		// Storeから Stateを取得する
		// getCertifications() {
		// 	return this.$store.state.EditSkill.skill.certifications;
		// },

		// TOEIC,TOEFLスコアのエラーメッセージチェック
		CheckErrorMessageScore() {
			return this.getErrorMessage(this.errors.toeic_score) || this.getErrorMessage(this.errors.toefl_score);
		},

		// 入力データをバインディング
		// 保有資格
		skill: {
			get() {
				return this.$store.state.EditSkill.skill;
			},
		},
		certifications: {
			get() {
				return this.$store.state.EditSkill.skill.certifications;
			},
			set(str) {
				this.$store.commit('EditSkill/set_certifications', str);
			}
		},
		// 英語スキル
		reading_english_level: {
			get() {
				return this.$store.state.EditSkill.skill.reading_english_level;
			},
			set(str) {
				this.$store.commit('EditSkill/set_reading_english_level', str);
			}
		},
		speaking_english_level: {
			get() {
				return this.$store.state.EditSkill.skill.speaking_english_level;
			},
			set(str) {
				this.$store.commit('EditSkill/set_speaking_english_level', str);
			}
		},
		writing_english_level: {
			get() {
				return this.$store.state.EditSkill.skill.writing_english_level;
			},
			set(str) {
				this.$store.commit('EditSkill/set_writing_english_level', str);
			}
		},
		toeic_score: {
			get() {
				return this.$store.state.EditSkill.skill.toeic_score;
			},
			set(str) {
				this.$store.commit('EditSkill/set_toeic_score', str);
			}
		},
		toefl_score: {
			get() {
				return this.$store.state.EditSkill.skill.toefl_score;
			},
			set(str) {
				this.$store.commit('EditSkill/set_toefl_score', str);
			}
		},
		// その他の語学スキル
		others_language: {
			get() {
				return this.$store.state.EditSkill.skill.others_language;
			},
			set(str) {
				this.$store.commit('EditSkill/set_others_language', str);
			}
		},
		input_others_language: {
			get() {
				return this.$store.state.EditSkill.skill.input_others_language;
			},
			set(str) {
				this.$store.commit('EditSkill/set_input_others_language', str);
			}
		},
		reading_others_language_level: {
			get() {
				return this.$store.state.EditSkill.skill.reading_others_language_level;
			},
			set(str) {
				this.$store.commit('EditSkill/set_reading_others_language_level', str);
			}
		},
		speaking_others_language_level: {
			get() {
				return this.$store.state.EditSkill.skill.speaking_others_language_level;
			},
			set(str) {
				this.$store.commit('EditSkill/set_speaking_others_language_level', str);
			}
		},
		writing_others_language_level: {
			get() {
				return this.$store.state.EditSkill.skill.writing_others_language_level;
			},
			set(str) {
				this.$store.commit('EditSkill/set_writing_others_language_level', str);
			}
		},

		// その他言語入力フォームの表示フラグ
		isSelectOthersLanguage: {
			get() {
				return this.$store.state.EditSkill.isSelectOthersLanguage;
			},
		},
		// ボタン連打を無効化
		isClickedSkill: {
			get() {
				return this.$store.state.EditSkill.isClicked;
			},
		},
		// 処理中の画面表示
		isLoading: {
			get() {
				return this.$store.state.EditSkill.isLoading;
			},
		},
	},

	mounted(){
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 4);
		// 資格・スキルデータ取得
		this.getSkill();
	},
}
</script>
