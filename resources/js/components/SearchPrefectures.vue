<template>
	<transition name="modal">
		<Modal v-show="isShowModal('typePrefectures')">
			<template slot="body">
				<div class="c-modal__body--l c-selectModal">
					<div class="c-modal__title">勤務地を選択してください。</div>

					<!-- 検索方式の選択タブ -->
					<div class="c-tab">
						<ul class="c-tab__menu c-selectModal__tabMenu">
							<li class="c-tab__container c-selectModal__tabContainer">
								<ul class="c-tab__grorp  c-selectModal__tabGroup">
									<li class="c-tab__item c-selectModal__tabItem" @click="selectTab('typePrefectures', 1)" :class="{'active': isActiveTab('typePrefectures') == 1}">
										<span class="c-tab__title" :class="{'active': isActiveTab('typePrefectures') == 1}">
											<div class="c-tab__inner">
												<span>フリーワードから探す</span>
											</div>
										</span>
									</li>
									<li class="c-tab__item c-selectModal__tabItem" @click="selectTab('typePrefectures', 2)" :class="{'active': isActiveTab('typePrefectures') == 2}">
										<span class="c-tab__title" :class="{'active': isActiveTab('typePrefectures') == 2}">
											<div class="c-tab__inner">
												<span>エリアから探す</span>
											</div>
										</span>
									</li>
								</ul>
							</li>
						</ul>

						<!-- タブ以下のコンテンツ -->
						<div class="c-tab__contents">

							<!-- フリーワード検索 -->
							<template v-if="isActiveTab('typePrefectures') == 1">
								<div class="c-tab__contentSet">

									<!-- フリーワード検索で見つからない場合の入力画面 -->
									<template v-if="isNoIndex('typePrefectures')">
										<div class="c-selectModal__freeWordInput">
											<div class="c-selectModal__label">
												お探しの勤務地が見つからない場合は、地名を入力してください。
											</div>

											<textarea v-model="inputFreeword" class="c-selectModal__textarea" 
											placeholder="〇〇市" name="inputFreeWord" id="InputFreeWord"></textarea>

											<div class="c-selectModal__backLinkArea">
												<a href="#" class="c-selectModal__backLink" @click="backSearchFreeword('typePrefectures')">
													<i class="fa-solid fa-chevron-right c-selectModal__backIcon"></i>検索に戻る
												</a>
											</div>
										</div>
									</template>

									<!-- フリーワード検索画面 -->
									<template v-else>
										<!-- 選択中リスト -->
										<div v-if="PrefecturesArray.length > 0" class="c-selectModal__selectSet">
											<div class="c-selectModal__selectText">選択中</div>
											<ul class="c-checkBoxList">
												<li v-for="prefectures in prefecturesData" :key="'selected:' + prefectures.name" class="c-checkBoxList__item--freeword">
													<label class="c-label" :class="{checked: checkItems('typePrefectures', prefectures.name)}" :for="'selected:' + prefectures.name">
														<input v-model="PrefecturesArray" class="u-none" type="checkbox" 
															:value="prefectures.name" :name="prefectures.name" :id="'selected:' + prefectures.name" 
															@change="checkItemsCategory('typePrefectures', 0, prefectures.name), changeActiveCheckArray('typePrefectures', prefectures.name)">
															<i v-if="isActiveCheck('typePrefectures', prefectures.name)" class="fa-solid fa-check c-checkmark"></i><span>{{ prefectures.name }}</span>
													</label>
												</li>
											</ul>
										</div>

										<!-- 検索ボックス -->
										<input v-model="freeword" @keyup="incrementalSearch('typePrefectures')" 
											class="c-input--l c-selectModal__serachBox" 
											id="freeword" name="freeword" type="text" placeholder="地名" autocomplete="on">

										<!-- インクリメンタルサーチの地名リスト -->
										<template v-if="suggestList('typePrefectures').length > 0">
											<ul class="c-checkBoxList">
												<li v-for="prefectures in suggestList('typePrefectures')" :key="prefectures" class="c-checkBoxList__item--column3">
													<label class="c-label" :class="{checked: checkItems('typePrefectures', prefectures)}" :for="prefectures">
														<input v-model="PrefecturesArray" class="u-none" type="checkbox" 
															:value="prefectures" :name="prefectures" :id="prefectures" 
															@change="checkItemsCategory('typePrefectures', 0, prefectures), changeActiveCheckArray('typePrefectures', prefectures)">
															<i v-if="isActiveCheck('typePrefectures', prefectures)" class="fa-solid fa-check c-checkmark"></i><span>{{ prefectures }}</span>
													</label>
												</li>
											</ul>
											<!-- お探しの地名が見つからない場合ボタン -->
											<div class="c-selectModal__noIndex">
												<a href="#" class="c-selectModal__noIndexButton" @click="noIndex('typePrefectures')">お探しの地名が見つからない場合
													<i class="fa-solid fa-chevron-right c-selectModal__noIndexIcon"></i>
												</a>
											</div>
										</template>
									</template>
								</div>
							</template>

							<!-- カテゴリ検索 -->
							<template v-else-if="isActiveTab('typePrefectures') == 2">
								<div class="c-tab__contentSet u-p0">

									<!-- テーブル -->
									<div v-if="isShowTable('typePrefectures')" class="c-searchTable">
										<!-- カテゴリ名 -->
										<div class="c-searchTable__categoryGroup">
											<!-- クリックで「selected」クラスがつく -->
											<div class="c-searchTable__category" 
												v-for="prefectures in prefecturesData" :key="'p' + prefectures.area_id" 
												@click="selectCategory('typePrefectures', prefectures.area_id)"
												:class="{selected: categoryNum('typePrefectures') == prefectures.area_id, checked: isCheckedCategory('typePrefectures', prefectures.area_id)}">
												<span class="c-searchTable__categoryTitle">{{ prefectures.name }}</span>
											</div>
										</div>
										<!-- チェックリスト -->
										<div class="c-searchTable__selectGroup">
											<div class="c-searchTable__selectList" :class="{selected: categoryNum('typePrefectures') == prefectures.area_id}"
												v-for="prefectures in prefecturesData" :key="'c' + prefectures.area_id">
												<div class="c-searchTable__selectTitle">{{ prefectures.name }}</div>
												<ul class="c-checkBoxList">
													<!-- <template v-if="prefectures.data.length != 0"> -->
														<li v-for="pref in prefectures.data" :key="pref.id" class="c-checkBoxList__item">
															<!-- クリックされたら「カテゴリ」「labelタグ」に「checked」クラスがつく -->
															<label class="c-label" :class="{checked: checkItems('typePrefectures', pref.name)}" :for="pref.id">
																<input v-model="PrefecturesArray" class="u-none" type="checkbox" 
																	:value="pref.name" :name="pref.name" :id="pref.id" 
																	@change="checkItemsCategory('typePrefectures', 0, pref.name), changeActiveCheckArray('typePrefectures', pref.name)">
																	<i v-if="isActiveCheck('typePrefectures', pref.name)" class="fa-solid fa-check c-checkmark"></i><span>{{ pref.name }}</span>
															</label>
														</li>
													<!-- </template>

													その他カテゴリ
													<template v-else>
														<div class="c-selectModal__freeWordInput">
															<div class="c-selectModal__label">
																お探しの勤務地が見つからない場合は、地名を入力してください。
															</div>
															<textarea v-model="inputFreeword" class="c-selectModal__textarea" 
															placeholder="〇〇市" name="inputFreeWord" id="InputFreeWord"></textarea>
														</div>
													</template> -->
												</ul>
											</div>
										</div>
									</div>

									<!-- アコーディオン -->
									<div v-else class="c-searchTable">
										<Accordion v-for="prefectures in prefecturesData" :key="'p' + prefectures.area_id">
											<div slot="title">
												<!-- カテゴリ名 -->
												<div class="c-searchTable">
													<div class="c-searchTable__category" @click="selectCategory('typePrefectures', prefectures.area_id)"
														:class="{checked: isCheckedCategory('typePrefectures', prefectures.area_id)}">
														<span class="c-searchTable__categoryTitle">{{ prefectures.name }}</span>
													</div>
												</div>
											</div>
											<div class="details" slot="detail">
												<!-- チェックリスト -->
												<div class="c-searchTable__selectGroup">
													<div class="c-searchTable__selectList">
														<ul class="c-checkBoxList">
															<template v-if="prefectures.data.length != 0">
																<li v-for="pref in prefectures.data" :key="pref.id" class="c-checkBoxList__item">
																	<!-- クリックされたら「カテゴリ」「labelタグ」に「checked」クラスがつく -->
																	<label class="c-label" :class="{checked: checkItems('typePrefectures', pref.name)}" :for="pref.id">
																		<input v-model="PrefecturesArray" class="u-none" type="checkbox" 
																			:value="pref.name" :name="pref.name" :id="pref.id" 
																			@change="checkItemsCategory('typePrefectures', 0, pref.name), changeActiveCheckArray('typePrefectures', pref.name)">
																			<i v-if="isActiveCheck('typePrefectures', pref.name)" class="fa-solid fa-check c-checkmark"></i><span>{{ pref.name }}</span>
																	</label>
																</li>
															</template>

															<!-- その他カテゴリ -->
															<template v-else>
																<div class="c-selectModal__freeWordInput">
																	<div class="c-selectModal__label">
																		お探しの勤務地が見つからない場合は、地名を入力してください。
																	</div>
																	<textarea v-model="inputFreeword" class="c-selectModal__textarea" 
																	placeholder="〇〇市" name="inputFreeWord" id="InputFreeWord"></textarea>
																</div>
															</template>
														</ul>
													</div>
												</div>
											</div>
										</Accordion>
									</div>
								</div>
							</template>
							<!-- カテゴリ検索ここまで -->
						</div>
						<!-- タブ以下コンテンツここまで -->
					</div>

					<div class="c-selectModal__decideButtonArea">
						<button class="c-selectModal__decideButton" @click.prevent="close('typePrefectures'), addInputFreeword('typePrefectures', inputFreeword)">決定する</button>
					</div>
				</div>
			</template>
		</Modal>
	</transition>
</template>

<script>
import Accordion from './Accordion.vue';
import SearchModalMixin from '../mixin/SearchModalMixin.vue';
import Modal from './Modal.vue';
import Prefectures from '../mixin/Prefectures.vue';

export default {
	components: {
		"Accordion": Accordion,
		"Modal": Modal,
	},
	mixins: [ Prefectures, SearchModalMixin ],
	props: [
	],
	pref(){
		return {
		}
	},
	methods:{
	},
	
	computed:{
		// 入力データをバインディング
		PrefecturesArray: {
			get() {
				return this.$store.state.Prefectures.items;
			},
			set(data) {
				this.$store.commit('Prefectures/set_certifications', data);
			}
		},
		freeword: {
			get() {
				return this.$store.state.Prefectures.freeword;
			},
			set(str) {
				this.$store.commit('Prefectures/setFreeword', str);
			}
		},
		inputFreeword: {
			get() {
				return this.$store.state.Prefectures.inputFreeword;
			},
			set(str) {
				this.$store.commit('Prefectures/setInputFreeword', str);
			}
		},
	},

	mounted(){
		this.handleResize();
		// 画面幅を取得するイベントを設定
		window.addEventListener('resize', this.handleResize());
	},
	beforeDestroy: function () {
		window.removeEventListener('resize', this.handleResize());
	}
}
</script>