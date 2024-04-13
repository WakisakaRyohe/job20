<template>
	<transition name="modal">
		<Modal v-show="isShowModal(type)">
			<template slot="body">
				<div class="c-modal__body--l">
					<div class="c-selectModal">
						<!-- モーダルを閉じるボタン -->
						<i class="fas fa-times c-selectModal__closeButton" @click.prevent="close(type)"></i>

						<!-- タイトル -->
						<div class="c-selectModal__title">
							<div class="c-selectModal__titleText">
								<i class="fa-regular fa-square-check"></i>
								<span class="">{{ title }}</span>
							</div>
						</div>

						<section class="c-section--selectList">
							<div class="c-section__tab"  :class="{pt5: !isFreeword}">
								<!-- 検索方式の選択タブ -->
								<div class="c-tab">
									<ul v-if="isFreeword" class="c-tab__menu--selectList">
										<li class="c-tab__container--selectList">
											<ul class="c-tab__grorp--selectList">
												<li class="c-tab__item--selectList" @click="selectTab(type, 1)" :class="{'active': isActiveTab(type) == 1}">
													<span class="c-tab__title" :class="{'active': isActiveTab(type) == 1}">
														<div class="c-tab__inner">
															<span>フリーワードから探す</span>
														</div>
													</span>
												</li>
												<li class="c-tab__item--selectList" @click="selectTab(type, 2)" :class="{'active': isActiveTab(type) == 2}">
													<span class="c-tab__title" :class="{'active': isActiveTab(type) == 2}">
														<div class="c-tab__inner">
															<span>カテゴリから探す</span>
														</div>
													</span>
												</li>
											</ul>
										</li>
									</ul>
								</div>
								<!-- 検索方式の選択タブここまで -->
							</div>

							<!-- タブ以下コンテンツ -->
							<!-- フリーワード検索 -->
							<div v-if="isActiveTab(type) == 1" class="c-section__container--selectList">
								
								<!-- フリーワード検索で資格が見つからない場合の入力画面 -->
								<template v-if="isNoIndex(type)">
									<div class="c-selectModal__freeWordInput">
										<div class="c-selectModal__label">
											お探しの資格が見つからない場合は、資格名を入力してください。
										</div>
										<textarea @keyup="formChange()" v-model.trim="inputFreeword" class="c-selectModal__textarea" 
											placeholder="〇〇検定" name="inputFreeWord" id="InputFreeWord"></textarea>
										<div class="c-selectModal__errMsg" v-if="getErrorMessage(errors.inputFreeword)">{{ getErrorMessage(errors.inputFreeword) }}</div>
										<div class="c-selectModal__backLinkArea">
											<a href="#" class="c-selectModal__backLink" @click="backSearchFreeword(type)">
												<i class="fa-solid fa-chevron-right c-selectModal__backIcon"></i>検索に戻る
											</a>
										</div>
									</div>
								</template>

								<!-- フリーワード検索画面 -->
								<template v-else>
									<!-- 選択中の資格リスト -->
									<div v-if="vuexArrayData.length > 0" class="c-selectModal__selectSet">
										<div class="c-selectModal__selectText">選択中</div>
										<ul class="c-checkBoxList">
											<li v-for="item in vuexArrayData" :key="'list:' + item" class="c-checkBoxList__item--freeword">
												<label class="c-checkBoxList__label" :class="{checked: checkItems(type, item)}" :for="'list:' + item">
													<input v-if="type === 'typeCertification'" v-model="certifications" class="u-none" type="checkbox" 
														:value="item" :name="item" :id="'list:' + item" 
														@change="formChange(), checkItemsCategory(type, 0, item), changeActiveCheckArray(type, item)">
													<i v-if="isActiveCheck(type, item)" class="fa-solid fa-check c-checkmark"></i><span>{{ item }}</span>
												</label>
											</li>
										</ul>
									</div>
									<!-- 検索ボックス -->
									<input v-model="freeword" @keyup="formChange(), incrementalSearch(type)" 
										class="c-input--l c-selectModal__serachBox" 
										id="freeword" name="freeword" type="text" placeholder="資格名" autocomplete="on">
									<!-- インクリメンタルサーチの資格リスト -->
									<template v-if="suggestList(type).length > 0">
										<ul class="c-checkBoxList">
											<li v-for="item in suggestList(type)" :key="item" class="c-checkBoxList__item--column3">
												<label class="c-checkBoxList__label" :class="{checked: checkItems(type, item)}" :for="item">
													<input v-if="type === 'typeCertification'" v-model="certifications" class="u-none" type="checkbox" 
														:value="item" :name="item" :id="item" 
														@change="formChange(), checkItemsCategory(type, 0, item), changeActiveCheckArray(type, item)">
													<i v-if="isActiveCheck(type, item)" class="fa-solid fa-check c-checkmark"></i><span>{{ item }}</span>
												</label>
											</li>
										</ul>
										<!-- お探しの資格が見つからない場合ボタン -->
										<div class="c-selectModal__noIndex">
											<a href="#" class="c-selectModal__noIndexButton" @click="noIndex(type)">
												お探しの資格が<br class="u-tabShow">見つからない場合
												<i class="fa-solid fa-chevron-right c-selectModal__noIndexIcon"></i>
											</a>
										</div>
									</template>
								</template>
							</div>
							<!-- フリーワード検索ここまで -->

							<!-- カテゴリ検索 -->
							<div v-else-if="isActiveTab(type) == 2"  class="c-section__container--selectList u-p0">
								<!-- テーブル -->
								<div v-if="isShowTable(type)" class="c-searchTable" :class="{height100: type === 'typeCommitment'}">
									<!-- カテゴリ名 -->
									<div class="c-searchTable__categoryGroup">
										<!-- クリックで「selected」クラスがつく -->
										<div class="c-searchTable__category" 
											v-for="item in arrayData" :key="'c' + item.id" 
											@click="selectCategory(type, item.id)"
											:class="{selected: categoryNum(type) === item.id, checked: isCheckedCategory(type, item.id), height20: type === 'typeCommitment'}">
											<span class="c-searchTable__categoryTitle">{{ item.name }}</span>
										</div>
									</div>
									<!-- チェックリスト -->
									<div class="c-searchTable__selectGroup" :class="{listHeightAuto: type === 'typeCommitment'}">
										<div class="c-searchTable__selectList" :class="{selected: categoryNum(type) === item.id}"
											v-for="item in arrayData" :key="'c' + item.id">
											<div class="c-searchTable__selectTitle">{{ item.name }}</div>
											<ul class="c-checkBoxList">
												<template v-if="item.data.length != 0">
													<li v-for="data in item.data" :key="data.id" class="c-checkBoxList__item">
														<!-- クリックされたら「カテゴリ」「labelタグ」に「checked」クラスがつく -->
														<label class="c-checkBoxList__label" :class="{checked: checkItems(type, data.name)}" :for="data.id">
															<input v-if="type === 'typeCertification'" v-model="certifications" class="u-none" type="checkbox" 
																:value="data.name" :name="data.name" :id="data.id" 
																@change="formChange(), checkItemsCategory(type, data.category_id, null), changeActiveCheckArray(type, data.name)">
															<input v-else-if="type === 'typeCommitment'" v-model="commitments" class="u-none" type="checkbox" 
																:value="data.name" :name="data.name" :id="data.id" 
																@change="formChange(), checkItemsCategory(type, data.category_id, null), changeActiveCheckArray(type, data.name)">
															<i v-if="isActiveCheck(type, data.name)" class="fa-solid fa-check c-checkmark"></i><span>{{ data.name }}</span>
														</label>
													</li>
												</template>
												<!-- その他カテゴリ -->
												<template v-else>
													<div class="c-selectModal__freeWordInput">
														<div class="c-selectModal__label">
															お探しの資格が見つからない場合は、資格名を入力してください。
														</div>
														<textarea @keyup="formChange()" v-model.trim="inputFreeword" class="c-selectModal__textarea" 
														placeholder="〇〇検定" name="inputFreeWord" id="InputFreeWord"></textarea>
													</div>
												</template>
											</ul>
										</div>
									</div>
								</div>

								<!-- アコーディオン -->
								<div v-else class="c-searchTable">
									<Accordion v-for="item in arrayData" :key="'c' + item.id" :searchFlg="searchFlg">
										<div slot="title">
											<!-- カテゴリ名 -->
											<div class="c-searchTable__categoryGroup">
												<div @click="selectCategory(type, item.id)" class="c-searchTable__category" 
													:class="{checked: isCheckedCategory(type, item.id)}">
													<span class="c-searchTable__categoryTitle">{{ item.name }}</span>
												</div>
											</div>
										</div>
										<div class="c-accordion__details" slot="detail">
											<!-- チェックリスト -->
											<div class="c-searchTable__selectGroup" :class="{heightAuto: type === 'typeCommitment'}">
												<div class="c-searchTable__selectList">
													<ul class="c-checkBoxList">
														<template v-if="item.data.length != 0">
															<li v-for="data in item.data" :key="data.id" class="c-checkBoxList__item">
																<!-- クリックされたら「カテゴリ」「labelタグ」に「checked」クラスがつく -->
																<label class="c-checkBoxList__label" :class="{checked: checkItems(type, data.name)}" :for="'aco' + data.id">
																	<input v-if="type === 'typeCertification'" v-model="certifications" class="u-none" type="checkbox" 
																		:value="data.name" :name="data.name" :id="'aco' + data.id" 
																		@change="formChange(), checkItemsCategory(type, data.category_id, null), changeActiveCheckArray(type, data.name)">
																	<input v-else-if="type === 'typeCommitment'" v-model="commitments" class="u-none" type="checkbox" 
																		:value="data.name" :name="data.name" :id="'aco' + data.id" 
																		@change="formChange(), checkItemsCategory(type, data.category_id, null), changeActiveCheckArray(type, data.name)">
																	<i v-if="isActiveCheck(type, data.name)" class="fa-solid fa-check c-checkmark"></i><span>{{ data.name }}</span>
																</label>
															</li>
														</template>
														<!-- その他カテゴリ -->
														<template v-else>
															<div class="c-selectModal__freeWordInput">
																<div class="c-selectModal__label">
																	お探しの資格が見つからない場合は、資格名を入力してください。
																</div>
																<textarea @keyup="formChange()" v-model.trim="inputFreeword" class="c-selectModal__textarea" 
																placeholder="〇〇検定" name="inputFreeWord" id="InputFreeWord"></textarea>
															</div>
														</template>
													</ul>
												</div>
											</div>
										</div>
									</Accordion>
								</div>
								<!-- アコーディオンここまで -->
							</div>
							<!-- カテゴリ検索ここまで -->
							<!-- タブ以下コンテンツここまで -->
						</section>

						<!-- 決定ボタン -->
						<div class="c-selectModal__decideButtonArea">
							<button type="button" :disabled="isDisabled" :class="{disabled: isDisabled}" class="c-selectModal__decideButton" @click.prevent="close(type), addInputFreeword(type, inputFreeword)">決定する</button>
						</div>
					</div>
				</div>
			</template>
		</Modal>
	</transition>
</template>

<script>
import Accordion from './Accordion.vue';
import Certifications from '../mixin/Certifications.vue';
import Commitments from '../mixin/Commitments.vue';
import FormChanged from '../mixin/FormChanged.vue';
import Modal from './Modal.vue';
import SearchModalMixin from '../mixin/SearchModalMixin.vue';
import Validation from '../mixin/Validation.vue';

export default {
	props: [
		"type",
		"title",
		"isFreeword",
		"searchFlg",
	],

	components: {
		"Accordion": Accordion,
		"Modal": Modal,
	},

	mixins: [ Certifications, Commitments, FormChanged, SearchModalMixin, Validation ],

	data(){
		return {
			errors: {
				inputFreeword: [],
			},
		}
	},

	// リアルタイムバリデーション
	watch: {
		// 入力した資格名
		inputFreeword: {
			handler: function () {
				this.isDisabled = false;
				this.errors.inputFreeword = [];

				// 入力した資格名が50文字以上の場合
				if(this.inputFreeword.length > 50){
					this.errors.inputFreeword.push('50文字以下で入力してください。');
				}

				// エラーがなければボタンが押せるようになる
				if(this.getErrorMessage(this.errors.inputFreeword)){
					this.isDisabled = true;
				}
			},
			immediate: true,
		},
	},

	methods:{
	},

	computed:{
		arrayData() {
			let data = [];
			switch(this.type){
				case 'typeCertification':
					data = this.certificationsData;
					break;
				case 'typeCommitment':
					data = this.commitmentsData;
					break;
			}
			return data;
		},
		vuexArrayData() {
			switch(this.type){
				case 'typeCertification':
					return this.$store.state.EditSkill.skill.certifications;
				case 'typeCommitment':
					return this.$store.state.Commitment.items;
			}
		},
	},
}
</script>

<style>
.pt5{
	padding-top: 5px;
}
.height100{
	height: 100%;
}
.height20{
	height: 20%;
}
.heightAuto{
	height: auto;
}
.listHeightAuto{
	height: auto;
}
</style>