<template>
	<div>
		<!-- パンくずリスト -->
		<BreadCrumb :category="category" :title="title"></BreadCrumb>

		<div class="p-editProfile c-inner--l">
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

							<h3 class="c-form__title">プロフィール</h3>

							<table class="c-table">
								<tbody>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">氏名</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-flexInput">
												<input @keyup="formChange()" v-model.lazy.trim="last_name" class="c-input--s p-profile__name" id="last_name" 
													name="last_name" type="text" placeholder="性を入力" autocomplete="on"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.last_name) )}">
												<input @keyup="formChange()" v-model.lazy.trim="first_name" class="c-input--s p-profile__name" id="first_name" 
													name="first_name" type="text" placeholder="名を入力" autocomplete="on"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.first_name) )}">
											</div>
											<div class="c-table__errMsg" v-show="CheckErrorMessageName">{{ getErrorMessage(errors.last_name) }} <br v-show="getErrorMessage(errors.last_name)"> {{ getErrorMessage(errors.first_name) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">氏名カナ</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-flexInput">
												<input @keyup="formChange()" v-model.lazy.trim="last_name_kana" class="c-input--s p-profile__name" id="last_name_kana" 
													name="last_name_kana" type="text" placeholder="性(カナ)を入力" autocomplete="on"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.last_name_kana) )}"> 
												<input @keyup="formChange()" v-model.lazy.trim="first_name_kana" class="c-input--s p-profile__name" id="first_name_kana" 
													name="first_name_kana" type="text" placeholder="名(カナ)を入力" autocomplete="on"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.first_name_kana) )}"> 
											</div>
											<div class="c-table__errMsg" v-show="CheckErrorMessageNameKana">{{ getErrorMessage(errors.last_name_kana) }} <br v-show="getErrorMessage(errors.last_name_kana)"> {{ getErrorMessage(errors.first_name_kana) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">生年月日</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-flexInput p-profile__birth">
												<div class="c-selectBox p-profile__year">
													<select @change="formChange()" v-model="year_of_birth" class="c-selectBox__select" id="year_of_birth" name="year_of_birth"
														:style="{backgroundColor: inputColor( getErrorMessage(errors.year_of_birth) )}">
														<option v-if="isWidthL || isWidthS" value>年</option>
														<option v-else value>年を選択</option>
														<option v-for="number in reverseNumberArray" :key="(number + 1946)" :value="(number + 1946)" :selected="year_of_birth == (number + 1946)">{{ (number + 1946) }}年</option>
													</select>
												</div>
												<div class="c-flexInput p-profile__birthSub">
													<div class="c-selectBox p-profile__month">
														<select @change="formChange()" v-model="month_of_birth" class="c-selectBox__select" id="month_of_birth" name="month_of_birth"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.month_of_birth) )}">
															<option v-if="isWidthL || isWidthS" value>月</option>
															<option v-else value>月を選択</option>
															<option v-for="n in 12" :key="n" :value="n" :selected="month_of_birth == n">{{ n }}月</option>
														</select>
													</div>
													<div class="c-selectBox p-profile__date">
														<select @change="formChange()" v-model="date_of_birth" class="c-selectBox__select" id="date_of_birth" name="date_of_birth"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.date_of_birth) )}">
															<option v-if="isWidthL || isWidthS" value>日</option>
															<option v-else value>日を選択</option>
															<option v-for="n in 31" :key="n" :value="n" :selected="date_of_birth == n">{{ n }}日</option>
														</select>
													</div>
													<span class="p-profile__age">{{ getAge() }}歳</span>
												</div>
											</div>
											<div class="c-table__errMsg p-editProfile__birthError" v-show="CheckErrorMessageBirth">
												{{ getErrorMessage(errors.year_of_birth) }}<br v-show="getErrorMessage(errors.year_of_birth)">
												{{ getErrorMessage(errors.month_of_birth) }}<br v-show="getErrorMessage(errors.month_of_birth)">
												{{ getErrorMessage(errors.date_of_birth) }} 
											</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">性別</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-flexInput c-radio">
												<label class="c-radio__label" for="man">
													<input @change="formChange()" v-model="sex" class="c-radio__button" type="radio" name="sex" id="man" :value="'男性'">男性
												</label>
												<label class="c-radio__label" for="woman">
													<input @change="formChange()" v-model="sex" class="c-radio__button" type="radio" name="sex" id="woman" :value="'女性'">女性
												</label>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.sex)">{{ getErrorMessage(errors.sex) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--p25">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">現住所</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<dl class="c-dl">
												<dt class="c-dl__dt">郵便番号</dt>
												<dd class="c-dl__dd">
													<div class="c-flexInput p-profile__zip">
														<input @keyup="formChange()" v-model.lazy.trim="zip" class="c-input--s p-profile__zipInput" id="zip" 
															name="zip" type="text" placeholder="0010064" autocomplete="on"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.zip) )}">
														<button type="button" class="p-profile__addressSearchButton" :class="{disabledZip: isDisabledZip}"
															@click.prevent="zipToAddress" :disabled="isDisabledZip">住所を入力</button>
													</div>
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.zip)">{{ getErrorMessage(errors.zip) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">都道府県</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--s">
														<select @change="formChange()" v-model="prefecture" class="c-selectBox__select" id="prefecture" name="prefecture"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.prefecture) )}">
															<option value>都道府県を選択</option>
															<option v-for="prefecture in prefectures" :key="prefecture.id" :value="prefecture.name" :selected="prefecture == prefecture.name">{{ prefecture.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.prefecture)">{{ getErrorMessage(errors.prefecture) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">市区町村</dt>
												<dd class="c-dl__dd">
													<input @keyup="formChange()" v-model.lazy.trim="municipalities" class="c-input--l" id="municipalities" 
														name="municipalities" type="text" placeholder="千代田区神田錦町3-1" autocomplete="on"
														:style="{backgroundColor: inputColor( getErrorMessage(errors.municipalities) )}">
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.municipalities)">{{ getErrorMessage(errors.municipalities) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">その他住所</dt>
												<dd class="c-dl__dd">
													<input @keyup="formChange()" v-model.lazy.trim="other_address" class="c-input--l" id="other_address" 
														name="other_address" type="text" placeholder="〇〇ビル 7F (任意)" autocomplete="on"
														:style="{backgroundColor: inputColor( getErrorMessage(errors.other_address) )}">
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.other_address)">{{ getErrorMessage(errors.other_address) }}</div>
												</dd>
											</dl>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--p25">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">電話番号</span>
											<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<input @keyup="formChange()" v-model.lazy.trim="tel" class="c-input--s" id="tel" 
												name="tel" type="text" placeholder="08012345678" autocomplete="on"
												:style="{backgroundColor: inputColor( getErrorMessage(errors.tel) )}"> 
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.tel)">{{ getErrorMessage(errors.tel) }}</div>
											<div class="c-attention">※ハイフンなし、半角数字で入力してください</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th--p25">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">最終学歴</span>
											<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<dl class="c-dl">
												<dt class="c-dl__dt">学校の種類</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--s">
														<select @change="formChange()" v-model="school_type" class="c-selectBox__select" id="school_type" name="school_type"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.school_type) )}"> 
															<option value>選択してください</option>
															<option v-for="school_type in school_types" :key="school_type.id" :value="school_type.name" :selected="school_type == school_type.name">{{ school_type.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.school_type)">{{ getErrorMessage(errors.school_type) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">学校名</dt>
												<dd class="c-dl__dd">
													<input @keyup="formChange()" v-model.lazy.trim="school_name" class="c-input--l" id="school_name" 
														name="school_name" type="text" placeholder="学校名を入力"autocomplete="on"
														:style="{backgroundColor: inputColor( getErrorMessage(errors.school_name) )}"> 								
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.school_name)">{{ getErrorMessage(errors.school_name) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">学部・学科</dt>
												<dd class="c-dl__dd">
													<input @keyup="formChange()" v-model.lazy.trim="faculty_and_department" class="c-input--l" id="faculty_and_department" 
														name="faculty_and_department" type="text" placeholder="学部・学科を入力" autocomplete="on"
														:style="{backgroundColor: inputColor( getErrorMessage(errors.faculty_and_department) )}"> 								
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.faculty_and_department)">{{ getErrorMessage(errors.faculty_and_department) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">文理区分</dt>
												<dd class="c-dl__bunri">
													<div class="c-flexInput c-radio">
														<label class="c-radio__label" for="literature">
															<input @change="formChange()" v-model="literature_or_science" class="c-radio__button" type="radio" name="literature_or_science" id="literature" :value="'文系'">文系
														</label>
														<label class="c-radio__label" for="science">
															<input @change="formChange()" v-model="literature_or_science" class="c-radio__button" type="radio" name="literature_or_science" id="science" :value="'理系'">理系
														</label>
													</div>
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.literature_or_science)">{{ getErrorMessage(errors.literature_or_science) }}</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">在籍期間</dt>
												<dd class="c-dl__dd">
													<div class="c-flexInput">
														<div class="c-selectBox p-profile__period">
															<select @change="formChange()" v-model="enrollment_year" class="c-selectBox__select" id="enrollment_year" name="enrollment_year"
																:style="{backgroundColor: inputColor( getErrorMessage(errors.enrollment_year) )}"> 
																<option v-if="isWidthS" value>入学年</option>
																<option v-else value>入学年を選択</option>
																<option v-for="number in reverseNumberArray" :key="(number + 1961)" :value="(number + 1961)" :selected="enrollment_year == (number + 1961)">{{ (number + 1961) }}年</option>
															</select>
														</div>
														<div class="c-selectBox__from">〜</div>
														<div class="c-selectBox p-profile__period">
															<select @change="formChange()" v-model="graduation_year" class="c-selectBox__select" id="graduation_year" name="graduation_year"
																:style="{backgroundColor: inputColor( getErrorMessage(errors.graduation_year) )}"> 
																<option v-if="isWidthS"  value>卒業年</option>
																<option v-else value>卒業年を選択</option>
																<option v-for="number in reverseNumberArray" :key="(number + 1964)" :value="(number + 1964)" :selected="graduation_year == (number + 1964)">{{ (number + 1964) }}年</option>
															</select>
														</div>
													</div>
													<div class="c-table__errMsg p-editProfile__periodError" v-show="CheckErrorMessagePeriod">
														{{ getErrorMessage(errors.enrollment_year) }}<br v-show="getErrorMessage(errors.enrollment_year)">
														{{ getErrorMessage(errors.graduation_year) }}<br v-show="getErrorMessage(errors.graduation_year)">
														{{ getErrorMessage(errors.period) }} 
													</div>
												</dd>
											</dl>
											<dl class="c-dl">
												<dt class="c-dl__dt">卒業区分</dt>
												<dd class="c-dl__dd">
													<div class="c-selectBox--s">
														<select @change="formChange()" v-model="graduation_type" class="c-selectBox__select" id="graduation_type" name="graduation_type"
															:style="{backgroundColor: inputColor( getErrorMessage(errors.graduation_type) )}"> 
															<option value>選択してください</option>
															<option v-for="graduation_type in graduation_types" :key="graduation_type.id" :value="graduation_type.name" :selected="graduation_type == graduation_type.name">{{ graduation_type.name }}</option>
														</select>
													</div>
													<div class="c-table__errMsg--mb10" v-if="getErrorMessage(errors.graduation_type)">{{ getErrorMessage(errors.graduation_type) }}</div>
												</dd>
											</dl>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">転職経験</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-selectBox--s">
												<select @change="formChange()" v-model="job_change_experience" class="c-selectBox__select" id="job_change_experience" name="job_change_experience"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.job_change_experience) )}"> 
													<option value>選択してください</option>
													<option value="転職経験なし" :selected="job_change_experience == 0">転職経験なし</option>
													<option v-for="n in 4" :key="n" :value="n+'回'" :selected="job_change_experience == n">{{ n }}回</option>
													<option value="5回以上" :selected="job_change_experience == 5">5回以上</option>
												</select>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.job_change_experience)">{{ getErrorMessage(errors.job_change_experience) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">就業状況</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-selectBox--s">
												<select @change="formChange()" v-model="employment_status" class="c-selectBox__select" id="employment_status" name="employment_status"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.employment_status) )}"> 
													<option value>選択してください</option>
													<option v-for="employment_status in employment_statuses" :key="employment_status.id" :value="employment_status.name" :selected="employment_status == employment_status.name">{{ employment_status.name }}</option>
												</select>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.employment_status)">{{ getErrorMessage(errors.employment_status) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">直近の年収</span>
												<span class="c-table__badge--require">必須</span>
											</div>
										</th>
										<td class="c-table__td">
											<div class="c-selectBox--s">
												<select @change="formChange()" v-model="latest_annual_income" class="c-selectBox__select" id="latest_annual_income" name="latest_annual_income"
													:style="{backgroundColor: inputColor( getErrorMessage(errors.latest_annual_income) )}"> 
													<option value>選択してください</option>
													<option value="200万円以下" :selected="latest_annual_income == 0">200万円以下</option>
													<option v-for="n in 15" :key="n" :value="getLatestAnnualIncome(n)" :selected="latest_annual_income == getLatestAnnualIncome(n)">{{ (n+4)*50 + 1 }} ~ {{ (n+5)*50 }}万円</option>
													<option v-for="n in 5" :key="n+15" :value="getLatestAnnualIncomeOver1000(n)" :selected="latest_annual_income == getLatestAnnualIncomeOver1000(n)">{{ 1001 + (n-1)*100 }} ~ {{ 1000 + n*100 }}万円</option>
													<option value="1501万円以上" :selected="latest_annual_income == 19">1501万円以上</option>
												</select>
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.latest_annual_income)">{{ getErrorMessage(errors.latest_annual_income) }}</div>
										</td>
									</tr>
									<tr class="c-table__tr">
										<th class="c-table__th u-p20">
											<div class="c-table__thUnit">
												<span class="c-table__thTitle">証明写真</span>
												<span class="c-table__badge--any">任意</span>
											</div>
										</th>
										<td class="c-table__td">
											<!-- リアルタイムプレビュー -->
											<div class="c-table__imageArea">
												<div v-if="imageData && !getErrorMessage(errors.id_photo)">
													<img class="" :src="imageData">
													<button type="button" class="c-btn--delete p-profile__imageDeleteBtn" @click.prevent="resetFile()">
														<i class="fa-solid fa-circle-xmark"></i><span class="u-tabHidden"></span>画像をリセット
													</button>
												</div>
												<img v-else-if="id_photo" :src = "imgPath + id_photo">
												<img v-else :src = "imgPath + 'user_icon.png'">
											</div>
											
											<!-- ドラッグ＆ドロップ -->
											<div class="p-profile__dropArea" :class="{dashedBorder: borderChange}"
												@dragenter.prevent="dragenter()" @dragleave.prevent="dragleave()">
												<div class="p-profile__dropButton">ファイルを選択</div>
												<span class="p-profile__dropText">または、ここに画像をドロップしてください</span>
												<input type="hidden" name="MAX_FILE_SIZE" value="7340032">
												<input type="file" id="id_photo" ref="file" class="p-profile__inputFile" 
													@change="formChange(), onFileChange($event)" 
													accept="image/jpeg, jpeg, image/png, png, image/gif, gif, image/heic, heic" multiple>										
											</div>
											<div class="c-table__errMsg" v-if="getErrorMessage(errors.id_photo)">{{ getErrorMessage(errors.id_photo) }}</div>
											<div class="c-attention">7MB以下の JPG、PNG, GIF, HEICファイルを選択してください。
												保存される画像は縦4:横3の比率になりますので、この比率に近い画像を推奨致します。
											</div>
										</td>
									</tr>
								</tbody>
							</table>
										
							<!-- 登録ボタン -->
							<div class="c-form__alignCenterArea">
								<div class="c-btnWrap">
									<input type="submit" @click.prevent="formSubmit(), updateProfile()" class="c-btn--m" 
									:class="[{disabled: isDisabled}, {'c-btn--clicked': isClickedProfile} ]" :disabled="isDisabled" :value="btnText">
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
import ImagePreview from '../mixin/ImagePreview.vue';
import Loading from '../components/Loading.vue';
import ProfileSelectBox from '../mixin/ProfileSelectBox.vue';
import Validation from '../mixin/Validation.vue';
import ValidationError from '../components/ValidationError.vue';
import ValidationProfile from '../mixin/ValidationProfile.vue';
import { Core as YubinBangoCore } from 'yubinbango-core2'

export default {
	components: {
		"BreadCrumb": BreadCrumb,
		"Loading": Loading,
		"ValidationError": ValidationError,
	},
	mixins: [ CheckEdit, FormChanged, HandleResize, ImagePreview, ProfileSelectBox, Validation, ValidationProfile ],
	props: [
		'propProfile',
	],
	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			// パンくずリストに渡すデータ
			title: 'プロフィールの登録・更新',
			category: {
				id: 4,
				name: 'WEB履歴書',
				path: '/job20/web_resume',
			},
			imgPath: 'https://d38rk2cibjrg07.cloudfront.net/job_change_20/',
			// 送信判定フラグ
			isSubmit: false,
			// エラーオブジェクト
			errors: {
				// 名前
				last_name: [],
				last_name_kana: [],
				first_name: [],
				first_name_kana: [],
				// 生年月日
				year_of_birth: [],
				month_of_birth: [],
				date_of_birth: [],
				// 性別
				sex: [],
				// 現住所
				zip: [],
				prefecture: [],
				municipalities: [],
				other_address: [],
				// 電話番号
				tel: [],
				// 学歴
				school_type: [],
				school_name: [],
				faculty_and_department: [],
				literature_or_science: [],
				period: [],
				enrollment_year: [],
				graduation_year: [],
				graduation_type: [],
				// その他
				job_change_experience: [],
				employment_status: [],
				latest_annual_income: [],
				// 画像
				id_photo: [],
			},
		}
	},

	methods: {
		// 年齢計算
		getAge() {
			this.$store.dispatch('EditProfile/getAge');
			return this.$store.state.EditProfile.profile.age;
		},

		// 住所検索
		zipToAddress() {
			const self = this;

			new YubinBangoCore(self.zip, function (addr) {
				// 住所がある場合
				if (addr.region_id) {
					self.$store.commit('EditProfile/setPrefecture', addr.region);
					self.$store.commit('EditProfile/setMunicipalities', addr.street);
					
				// 住所がない場合
				} else {
					self.errors.zip =  [];
					self.errors.zip.push('該当する住所がありません');
					self.isDisabledZip = true;
				}
			})
		},
		
		// プロフィール更新処理
		updateProfile: async function (){
			await this.$store.dispatch('EditProfile/updateProfile');

			// エラーがある場合vuexから代入して削除
			this.errors = this.$store.state.EditProfile.errors;
			this.$store.commit('EditProfile/setErrors', {});

			// 送信後にエラーがあればアラート表示
			this.isSubmit = true;

			// ボタンを初期状態に戻す
			this.isDisabled = true;
		},

		// プロフィールデータ取得
		getProfile: async function () {
			if(this.propProfile){
				// console.log('propsあり');
				this.$store.commit('EditProfile/setProfile', this.propProfile);

				// 入力されていない任意項目に空文字をセット(nullのままだとDBに文字列の「null」が登録される)
				if(this.propProfile.other_address === null){
					this.$store.commit('EditProfile/setOtherAddress', '');
				}

				// 編集画面と判定する
				this.editFlg = true;
				// ロード画面非表示
				this.$store.commit('EditProfile/setIsLoading', false);

			}else{
				// console.log('propsなし');

				// axiosで使うthisを格納
				const self = this;

				await axios.get('/job20/web/profile')
				.then(response => {
					if(response.data.successFlg){
						if(response.data.profile){
							self.$store.commit('EditProfile/setProfile', response.data.profile);

							// 入力されていない任意項目に空文字をセット(nullのままだとDBに文字列の「null」が登録される)
							if(response.data.profile.other_address === null){
								self.$store.commit('EditProfile/setOtherAddress', '');
							}

							// 編集画面か判定
							if(response.data.editFlg){
								// console.log('編集画面');
								// 編集画面と判定する
								self.editFlg = true;
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
				this.$store.commit('EditProfile/setIsLoading', false);
			}
		},
	},

	computed: {
		// csrf対策
		computedCsrf() {
		},
			
		// 名前のエラーメッセージチェック
		CheckErrorMessageName() {
			return this.getErrorMessage(this.errors.last_name) || this.getErrorMessage(this.errors.first_name);
		},
		CheckErrorMessageNameKana() {
			return this.getErrorMessage(this.errors.last_name_kana) || this.getErrorMessage(this.errors.first_name_kana);
		},
		// 生年月日のエラーメッセージチェック
		CheckErrorMessageBirth() {
			return this.getErrorMessage(this.errors.year_of_birth) || this.getErrorMessage(this.errors.month_of_birth) || this.getErrorMessage(this.errors.date_of_birth);
		},
		// 在籍期間のエラーメッセージチェック
		CheckErrorMessagePeriod() {
			return this.getErrorMessage(this.errors.enrollment_year) || this.getErrorMessage(this.errors.graduation_year) || this.getErrorMessage(this.errors.period);
		},

		reverseNumberArray() {
			const numberArray = Array.from(Array(60).keys());
			return numberArray.slice(9).reverse();
		},
		getLatestAnnualIncome: function(){
			return function(num){
				return ( (num+4)*50 + 1 ) + '~' + ( (num+5)*50 ) + '万円';
			}
		},
		getLatestAnnualIncomeOver1000: function(){
			return function(num){
				return ( 1001 + (num-1)*100 ) + '~' + ( 1000 + num*100 ) + '万円';
			}
		},

		// 入力データをバインディング
		profile: {
			get() {
				return this.$store.state.EditProfile.profile;
			},
		},
		// 名前
		last_name: {
			get() {
				return this.$store.state.EditProfile.profile.last_name;
			},
			set(str) {
				this.$store.commit('EditProfile/setLastName', str);
			}
		},
		last_name_kana: {
			get() {
				return this.$store.state.EditProfile.profile.last_name_kana;
			},
			set(str) {
				this.$store.commit('EditProfile/setLastNameKana', str);
			}
		},
		first_name: {
			get() {
				return this.$store.state.EditProfile.profile.first_name;
			},
			set(str) {
				this.$store.commit('EditProfile/setFirstName', str);
			}
		},
		first_name_kana: {
			get() {
				return this.$store.state.EditProfile.profile.first_name_kana;
			},
			set(str) {
				this.$store.commit('EditProfile/setFirstNameKana', str);
			}
		},
		// 生年月日
		year_of_birth: {
			get() {
				return this.$store.state.EditProfile.profile.year_of_birth;
			},
			set(num) {
				this.$store.commit('EditProfile/setYearOfBirth', num);
			}
		},
		month_of_birth: {
			get() {
				return this.$store.state.EditProfile.profile.month_of_birth;
			},
			set(num) {
				this.$store.commit('EditProfile/setMonthOfBirth', num);
			}
		},
		date_of_birth: {
			get() {
				return this.$store.state.EditProfile.profile.date_of_birth;
			},
			set(num) {
				this.$store.commit('EditProfile/setDateOfBirth', num);
			}
		},
		// 性別
		sex: {
			get() {
				return this.$store.state.EditProfile.profile.sex;
			},
			set(str) {
				this.$store.commit('EditProfile/setSex', str);
			}
		},
		// 住所
		zip: {
			get() {
				return this.$store.state.EditProfile.profile.zip;
			},
			set(str) {
				this.$store.commit('EditProfile/setZip', str);
			}
		},
		prefecture: {
			get() {
				return this.$store.state.EditProfile.profile.prefecture;
			},
			set(str) {
				this.$store.commit('EditProfile/setPrefecture', str);
			}
		},
		municipalities: {
			get() {
				return this.$store.state.EditProfile.profile.municipalities;
			},
			set(str) {
				this.$store.commit('EditProfile/setMunicipalities', str);
			}
		},
		other_address: {
			get() {
				return this.$store.state.EditProfile.profile.other_address;
			},
			set(str) {
				this.$store.commit('EditProfile/setOtherAddress', str);
			}
		},
		// 電話番号
		tel: {
			get() {
				return this.$store.state.EditProfile.profile.tel;
			},
			set(str) {
				this.$store.commit('EditProfile/setTel', str);
			}
		},
		// 学歴
		school_type: {
			get() {
				return this.$store.state.EditProfile.profile.school_type;
			},
			set(str) {
				this.$store.commit('EditProfile/setSchoolType', str);
			}
		},
		school_name: {
			get() {
				return this.$store.state.EditProfile.profile.school_name;
			},
			set(str) {
				this.$store.commit('EditProfile/setSchoolName', str);
			}
		},
		faculty_and_department: {
			get() {
				return this.$store.state.EditProfile.profile.faculty_and_department;
			},
			set(str) {
				this.$store.commit('EditProfile/setFacultyAndDepartment', str);
			}
		},
		literature_or_science: {
			get() {
				return this.$store.state.EditProfile.profile.literature_or_science;
			},
			set(str) {
				this.$store.commit('EditProfile/setLiteratureOrScience', str);
			}
		},
		enrollment_year: {
			get() {
				return this.$store.state.EditProfile.profile.enrollment_year;
			},
			set(num) {
				this.$store.commit('EditProfile/setEnrollmentYear', num);
			}
		},
		graduation_year: {
			get() {
				return this.$store.state.EditProfile.profile.graduation_year;
			},
			set(num) {
				this.$store.commit('EditProfile/setGraduationYear', num);
			}
		},
		graduation_type: {
			get() {
				return this.$store.state.EditProfile.profile.graduation_type;
			},
			set(str) {
				this.$store.commit('EditProfile/setGraduationType', str);
			}
		},
		// その他
		job_change_experience: {
			get() {
				return this.$store.state.EditProfile.profile.job_change_experience;
			},
			set(str) {
				this.$store.commit('EditProfile/setJobChangeExperience', str);
			}
		},
		employment_status: {
			get() {
				return this.$store.state.EditProfile.profile.employment_status;
			},
			set(str) {
				this.$store.commit('EditProfile/setEmploymentStatus', str);
			}
		},
		latest_annual_income: {
			get() {
				return this.$store.state.EditProfile.profile.latest_annual_income;
			},
			set(str) {
				this.$store.commit('EditProfile/setLatestAnnualIncome', str);
			}
		},
		// 画像
		id_photo: {
			get() {
				return this.$store.state.EditProfile.profile.id_photo;
			},
			set(str) {
				this.$store.commit('EditProfile/setIdPhoto', str);
			}
		},
		file: {
			get() {
				return this.$store.state.EditProfile.file;
			},
			set(file) {
				this.$store.commit('EditProfile/setFile', file);
			}
		},
		imageData: {
			get() {
				return this.$store.state.EditProfile.imageData;
			},
		},
		borderChange: {
			get() {
				return this.$store.state.EditProfile.borderChange;
			},
		},

		// ボタン連打を無効化
		isClickedProfile: {
			get() {
				return this.$store.state.EditProfile.isClicked;
			},
		},
		// 処理中の画面表示
		isLoading: {
			get() {
				return this.$store.state.EditProfile.isLoading;
			},
		},
	},

	mounted(){
		// 画像データはvueXに保存されないため、エラーがある場合はメッセージと共に削除
		if(this.getErrorMessage(this.errors.id_photo)){
			this.$store.commit('EditProfile/setImageData', '');
			this.$store.commit('EditProfile/resetErrorsIdPhoto', []);
		}

		// 各項目が空の場合は、エラーメッセージ削除
		// 名前
		if(this.last_name === ''){
			this.errors.last_name = [];
		}
		if(this.last_name_kana === ''){
			this.errors.last_name_kana = [];
		}
		if(this.first_name === ''){
			this.errors.first_name = [];
		}
		if(this.first_name_kana === ''){
			this.errors.first_name_kana = [];
		}
		// 生年月日
		if(this.year_of_birth === ''){
			this.errors.year_of_birth = [];
		}
		if(this.month_of_birth === ''){
			this.errors.month_of_birth = [];
		}
		if(this.date_of_birth === ''){
			this.errors.date_of_birth = [];
		}
		// 性別
		if(this.sex === ''){
			this.errors.sex = [];
		}
		// 住所
		if(this.zip === ''){
			this.errors.zip = [];
		}
		if(this.prefecture === ''){
			this.errors.prefecture = [];
		}
		if(this.municipalities === ''){
			this.errors.municipalities = [];
		}
		if(this.other_address === ''){
			this.errors.other_address = [];
		}
		// 電話番号
		if(this.tel === ''){
			this.errors.tel = [];
		}
		// 学歴
		if(this.school_type === ''){
			this.errors.school_type = [];
		}
		if(this.school_name === ''){
			this.errors.school_name = [];
		}
		if(this.faculty_and_department === ''){
			this.errors.faculty_and_department = [];
		}
		if(this.literature_or_science === ''){
			this.errors.literature_or_science = [];
		}
		if(this.enrollment_year === ''){
			this.errors.enrollment_year = [];
		}
		if(this.graduation_year === ''){
			this.errors.graduation_year = [];
		}
		if(this.enrollment_year === '' && this.graduation_year === ''){
			this.errors.period = [];
		}
		if(this.graduation_type === ''){
			this.errors.graduation_type = [];
		}
		// その他
		if(this.job_change_experience === ''){
			this.errors.job_change_experience = [];
		}
		if(this.employment_status === ''){
			this.errors.employment_status = [];
		}
		if(this.latest_annual_income === ''){
			this.errors.latest_annual_income = [];
		}

		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 4);

		// プロフィールデータ取得
		this.getProfile();
	}
}
</script>
