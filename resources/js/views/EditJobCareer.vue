<template>
	<div>
		<!-- パンくずリスト -->
		<BreadCrumb :category="category" :title="title"></BreadCrumb>
		
		<div class="p-editJobCareer c-inner--l">
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

							<h3 class="c-form__title--jobCareer">職務経歴</h3>

							<table class="c-table">
								<tbody>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">社名</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<input @keyup="formChange()" v-model.lazy.trim="job_careers_company_name" class="c-input--l" id="job_careers_company_name" 
												name="job_careers_company_name" type="text" placeholder="会社名を入力してください" autocomplete="on"
												:style="{backgroundColor: inputColor( getErrorMessage(errors.job_careers_company_name) )}">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.job_careers_company_name)">{{ getErrorMessage(errors.job_careers_company_name) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">在籍期間</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-flexInput p-editJobCareer__period">
												<div class="c-flexInput">
													<div class="c-selectBox p-editJobCareer__periodSelectBox">
														<select @change="formChange()" v-model="year_of_joining" class="c-selectBox__select" id="year_of_joining" name="year_of_joining"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.year_of_joining) )}"> 
															<option v-if="isWidthS" value>入社年</option>
															<option v-else value>入社年を選択</option>
															<option v-for="number in reverseNumberArray" :key="(number + 1961)" :value="(number + 1961)" :selected="year_of_joining == (number + 1961)">{{ (number + 1961) }}年</option>
														</select>
													</div>
													<div class="c-selectBox p-editJobCareer__periodSelectBox">
														<select @change="formChange()" v-model="month_of_joining" class="c-selectBox__select" id="month_of_joining" name="month_of_joining"
														:style="{backgroundColor: inputColor( getErrorMessage(errors.month_of_joining) )}">
															<option v-if="isWidthS" value>入社月</option>
															<option v-else value>入社月を選択</option>
															<option v-for="n in 12" :key="n" :value="n" :selected="month_of_joining == n">{{ n }}月</option>
														</select>
													</div>
												</div>
												<div>〜</div>
												<div class="c-flexInput">
													<div class="c-selectBox p-editJobCareer__periodSelectBox">
														<select @change="formChange()" v-model="year_of_retirement" class="c-selectBox__select" id="year_of_retirement" name="year_of_retirement"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.year_of_retirement) )}"> 
															<option v-if="isWidthS" value>退社年</option>
															<option v-else value>退社年を選択</option>
															<option v-for="number in reverseNumberArray" :key="(number + 1964)" :value="(number + 1964)" :selected="year_of_retirement == (number + 1964)">{{ (number + 1964) }}年</option>
														</select>
													</div>
													<div class="c-selectBox p-editJobCareer__periodSelectBox">
														<select @change="formChange()" v-model="month_of_retirement" class="c-selectBox__select" id="month_of_retirement" name="month_of_retirement"
														:style="{backgroundColor: inputColor( getErrorMessage(errors.month_of_retirement) )}">
															<option v-if="isWidthS" value>退社月</option>
															<option v-else value>退社月を選択</option>
															<option v-for="n in 12" :key="n" :value="n" :selected="month_of_retirement == n">{{ n }}月</option>
														</select>										
													</div>
												</div>
											</div>
											<div class="c-table__errMsg p-editJobCareer__periodError" v-show="CheckErrorMessagePeriod">
												{{ getErrorMessage(errors.year_of_joining) }}<br v-show="getErrorMessage(errors.year_of_joining)">
												{{ getErrorMessage(errors.month_of_joining) }}<br v-show="getErrorMessage(errors.month_of_joining)">
												{{ getErrorMessage(errors.year_of_retirement) }}<br v-show="getErrorMessage(errors.year_of_retirement)">
												{{ getErrorMessage(errors.month_of_retirement) }}<br v-show="getErrorMessage(errors.month_of_retirement)">
												{{ getErrorMessage(errors.period) }}
											</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">雇用形態</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-selectBox--s">
												<select @change="formChange()" v-model="employment_status" class="c-selectBox__select" id="employment_status" name="employment_status"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.employment_status) )}"> 
													<option value>選択してください</option>
													<option v-for="employment_status in job_careers_employment_statuses" :key="employment_status.id" :value="employment_status.name" :selected="employment_status == employment_status.name">{{ employment_status.name }}</option>
												</select>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.employment_status)">{{ getErrorMessage(errors.employment_status) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">業種</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-selectBox p-editJobCareer__industrySelectBox">
												<select @change="formChange()" v-model="job_careers_industry" class="c-selectBox__select" id="job_careers_industry" name="job_careers_industry"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.job_careers_industry) )}"> 
													<option value>選択してください</option>
													<option v-for="job_careers_industry in job_careers_industries" :key="job_careers_industry.id" :value="job_careers_industry.name" :selected="job_careers_industry == job_careers_industry.name">{{ job_careers_industry.name }}</option>
												</select>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.job_careers_industry)">{{ getErrorMessage(errors.job_careers_industry) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">従業員数</span>
												<span class="c-table__badge--any">任意</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-selectBox--s">
												<select @change="formChange()" v-model="number_of_employees" class="c-selectBox__select" id="number_of_employees" name="number_of_employees"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.number_of_employees) )}"> 
													<option value>選択してください</option>
													<option v-for="number_of_employee in number_of_employeesData" :key="number_of_employee.id" :value="number_of_employee.name" :selected="number_of_employee == number_of_employee.name">{{ number_of_employee.name }}</option>
												</select>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.number_of_employees)">{{ getErrorMessage(errors.number_of_employees) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">部署/役職</span>
												<span class="c-table__badge--any">任意</span>
											</div>
										</th>
										<td class="c-table__td">
											<input @keyup="formChange()" v-model.lazy.trim="department_or_post" class="c-input--l" id="department_or_post" 
											name="department_or_post" type="text" placeholder="部署/役職を入力してください" autocomplete="on"
											:style="{backgroundColor: inputColor( getErrorMessage(errors.department_or_post) )}">
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.department_or_post)">{{ getErrorMessage(errors.department_or_post) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th u-p20">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">職務内容</span>
												<span class="c-table__badge--any">任意</span>
											</div>
										</th>
										<td class="c-table__td">
											<textarea @keyup="formChange()" v-model="job_details" ref="textarea" class="c-input--textarea" id="job_details" 
												name="job_details" rows="10" placeholder="職務内容を入力してください" autocomplete="on"
												:style="[textarea_height, {backgroundColor: inputColor( getErrorMessage(errors.job_details) )} ]"></textarea>
											<div class="c-table__countText">現在 <span :style="{color: inputLengthColor(job_details, 2000)}">{{ inputLength(job_details) }}</span>文字 / 最大 2000文字</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.job_details)">{{ getErrorMessage(errors.job_details) }}</div>
										</td>
									</tr>
								</tbody>
							</table>
										
							<!-- 登録ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), updateJobCareer(id)" class="c-btn--m" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClickedJobCareer} ]" :disabled="isDisabled" :value="btnText">
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
import JobCareerSelectBox from '../mixin/JobCareerSelectBox.vue';
import Validation from '../mixin/Validation.vue';
import ValidationError from '../components/ValidationError.vue';
import ValidationJobCareer from '../mixin/ValidationJobCareer.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"Loading": Loading,
		"ValidationError": ValidationError,
	},
	mixins: [ CheckEdit, FormChanged, HandleResize, JobCareerSelectBox, Validation, ValidationJobCareer ],
	props: [
		'propJobCareer',
	],
	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// パンくずリストに渡すデータ
			title: '職務経歴の登録・更新',
			category: {
				id: 4,
				name: 'WEB履歴書',
				path: '/job20/web_resume',
			},
			id: (this.$route.params.id) ? this.$route.params.id : 0,
			// 送信判定フラグ
			isSubmit: false,
			// 初回にテキストエリアの高さ変更
			isResize: false,
			// エラーオブジェクト
			errors: {
				// 社名
				job_careers_company_name: [],
				// 在籍期間
				year_of_joining	: [],
				month_of_joining: [],
				year_of_retirement: [],
				month_of_retirement: [],
				period: [],
				// 雇用形態
				employment_status: [],
				// 業種
				job_careers_industry: [],
				// 従業員数
				number_of_employees: [],
				// 部署/役職
				department_or_post: [],
				// 職務内容
				job_details: [],
			},
		}
	},

	methods: {				
		// 職務経歴の更新処理
		updateJobCareer: async function (id){
			await this.$store.dispatch('EditJobCareer/updateJobCareer', {id: id});

			// エラーがある場合vuexから代入して削除
			this.errors = this.$store.state.EditJobCareer.errors;
			this.$store.commit('EditJobCareer/setErrors', {});

			// 送信後にエラーがあればアラート表示
			this.isSubmit = true;

			// ボタンを初期状態に戻す
			this.isDisabled = true;
		},

		// テキストエリアの高さ変更
		resizeTextarea(){
			if(this.$refs.textarea){
				this.$store.commit('EditJobCareer/set_textarea_height', "auto");
				this.$nextTick(()=>{
					this.$store.commit('EditJobCareer/set_textarea_height', this.$refs.textarea.scrollHeight + 'px');
				});
			}
		},

		// 職務経歴データ取得
		getJobCareer: async function (){
			// 職務経歴データをセット
			if(this.propJobCareer){
				// console.log('propsあり');
				this.$store.commit('EditJobCareer/set_job_career', this.propJobCareer);

				// 入力されていない任意項目に空文字をセット(nullのままだとDBに文字列の「null」が登録される)
				if(this.propJobCareer.number_of_employees === null){
					this.$store.commit('EditJobCareer/set_number_of_employees', '');
				}
				if(this.propJobCareer.department_or_post === null){
					this.$store.commit('EditJobCareer/set_department_or_post', '');
				}
				if(this.propJobCareer.job_details === null){
					this.$store.commit('EditJobCareer/set_job_details', '');
				}

				// 編集画面と判定する
				this.editFlg = true;
				this.$store.commit('WebResume/set_fromEditJobCareer', true);

				// ロード画面非表示
				this.$store.commit('EditJobCareer/setIsLoading', false);

			// 職務経歴情報をDBから取得
			}else{
				// console.log('propsなし');

				// axiosで使うthisを格納
				const self = this;

				await axios.get('/job20/web/job_career/' + this.$route.params.id)
				.then(response => {
					// 他人の職務経歴編集ページの場合は、閲覧権限なしページに遷移
					if(response.data.forbiddenFlg){
						self.$router.push({ name: 'forbidden'});
						return;
					}

					if(response.data.successFlg){
						if(response.data.job_career){
							self.$store.commit('EditJobCareer/set_job_career', response.data.job_career);

							// 入力されていない任意項目に空文字をセット(nullのままだとDBに文字列の「null」が登録される)
							if(response.data.number_of_employees === null){
								self.$store.commit('EditJobCareer/set_number_of_employees', '');
							}
							if(response.data.department_or_post === null){
								self.$store.commit('EditJobCareer/set_department_or_post', '');
							}
							if(response.data.job_details === null){
								self.$store.commit('EditJobCareer/set_job_details', '');
							}

							// 編集画面か判定
							if(response.data.editFlg){
								// console.log('編集画面');
								self.editFlg = true;
								self.$store.commit('WebResume/set_fromEditJobCareer', true);
							}
						}else{
							// console.log('登録画面');
							self.$store.commit('WebResume/set_fromEditJobCareer', false);
						}

						// ロード画面非表示
						self.$store.commit('EditJobCareer/setIsLoading', false);

					}else{
						self.$router.push({ name: 'error'}, () => {});	
					}

				}).catch(error => {
					console.log(error);
					self.$router.push({ name: 'error' });
				});
			}
		}
	},

	computed: {
		// csrf対策
		computedCsrf() {
		},

		reverseNumberArray() {
			const numberArray = Array.from(Array(60).keys());
			return numberArray.slice(9).reverse();
		},

		// テキストエリアの高さ返却
		textarea_height(){
			return {
				"height": this.$store.state.EditJobCareer.textarea_height,
			}
		},

		// 在籍期間のエラーメッセージチェック
		CheckErrorMessagePeriod() {
			return this.getErrorMessage(this.errors.year_of_joining) || this.getErrorMessage(this.errors.month_of_joining) || 
				this.getErrorMessage(this.errors.year_of_retirement) || this.getErrorMessage(this.errors.month_of_retirement) || 
				this.getErrorMessage(this.errors.period);
		},

		// 入力データをバインディング
		job_career: {
			get() {
				return this.$store.state.EditJobCareer.job_career;
			},
		},
		// 社名
		job_careers_company_name: {
			get() {
				return this.$store.state.EditJobCareer.job_career.job_careers_company_name;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_job_careers_company_name', str);
			}
		},
		// 在籍期間
		year_of_joining: {
			get() {
				return this.$store.state.EditJobCareer.job_career.year_of_joining;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_year_of_joining', str);
			}
		},
		month_of_joining: {
			get() {
				return this.$store.state.EditJobCareer.job_career.month_of_joining;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_month_of_joining', str);
			}
		},
		year_of_retirement: {
			get() {
				return this.$store.state.EditJobCareer.job_career.year_of_retirement;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_year_of_retirement', str);
			}
		},
		month_of_retirement: {
			get() {
				return this.$store.state.EditJobCareer.job_career.month_of_retirement;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_month_of_retirement', str);
			}
		},
		// 雇用形態
		employment_status: {
			get() {
				return this.$store.state.EditJobCareer.job_career.employment_status;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_employment_status', str);
			}
		},
		// 業種
		job_careers_industry: {
			get() {
				return this.$store.state.EditJobCareer.job_career.job_careers_industry;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_job_careers_industry', str);
			}
		},
		// 従業員数
		number_of_employees: {
			get() {
				return this.$store.state.EditJobCareer.job_career.number_of_employees;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_number_of_employees', str);
			}
		},
		// 部署/役職
		department_or_post: {
			get() {
				return this.$store.state.EditJobCareer.job_career.department_or_post;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_department_or_post', str);
			}
		},
		// 職務内容
		job_details: {
			get() {
				return this.$store.state.EditJobCareer.job_career.job_details;
			},
			set(str) {
				this.$store.commit('EditJobCareer/set_job_details', str);
			}
		},

		// 処理中の画面表示
		isLoading: {
			get() {
				return this.$store.state.EditJobCareer.isLoading;
			},
		},
		// ボタン連打を無効化
		isClickedJobCareer: {
			get() {
				return this.$store.state.EditJobCareer.isClicked;
			},
		},
	},

	mounted(){
		// console.log('mounted');

		// 各項目が空の場合は、エラーメッセージ削除
		// 社名
		if(this.job_careers_company_name === ''){
			this.errors.job_careers_company_name = [];
		}
		// 在籍期間		
		if(this.year_of_joining === ''){
			this.errors.year_of_joining = [];
		}
		if(this.month_of_joining === ''){
			this.errors.month_of_joining = [];
		}
		if(this.year_of_retirement === ''){
			this.errors.year_of_retirement = [];
		}
		if(this.month_of_retirement === ''){
			this.errors.month_of_retirement = [];
		}
		if(this.year_of_joining === '' && this.month_of_joining === '' && 
		   this.year_of_retirement === '' && this.month_of_retirement === ''){
			this.errors.period = [];
		}
		// 雇用形態
		if(this.employment_status === ''){
			this.errors.employment_status = [];
		}
		// 業種
		if(this.job_careers_industry === ''){
			this.errors.job_careers_industry = [];
		}

		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 4);

		// 職務経歴データ取得
		this.getJobCareer();
	},

	updated(){
		if(!this.isResize){
			// テキストエリアの高さ変更
			this.resizeTextarea();
			this.isResize = true;
		}
	}
}
</script>
