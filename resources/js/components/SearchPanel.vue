<template>
	<div>
		<SearchModal
			type="typeCommitment"
			title="こだわり条件を選択してください。"
			:isFreeword="false"
			:searchFlg="true"
		></SearchModal>

		<section class="c-section p-searchPanel">
			<div class="c-section__title p-searchPanel__title">求人検索<i v-if="!isShowSidebar" class="fas fa-times p-searchPanel__closeButton" @click="reverseActiveSidebar"></i></div>
			<div class="c-section__container u-p0">

				<!-- 検索パネル -->
				<form class="p-searchPanel__form">
					<!-- csrf対策 -->
					<input type="hidden" name="_token" :value="csrf">

					<ul class="p-searchPanel__list">
						<li class="p-searchPanel__item">
							<div class="p-searchPanel__itemTitle"><i class="fa-solid fa-briefcase p-searchPanel__icon"></i>職種</div>
							<div class="c-selectBox">
								<select v-model="occupation" class="c-selectBox__select" name="occupation">
									<option value="0">選択してください</option>
									<option v-for="item in occupationsData" :key="item.id" :value="item.id" :selected="item.id == prefecture">{{ item.name }}</option>
								</select>
							</div>
						</li>
						<li class="p-searchPanel__item">
							<div class="p-searchPanel__itemTitle"><i class="fa-solid fa-location-dot p-searchPanel__icon"></i>勤務地</div>
							<div class="c-selectBox">
								<select v-model="prefecture" class="c-selectBox__select" name="prefecture">
									<option value="0">選択してください</option>
									<option v-for="item in prefecturesData" :key="item.id" :value="item.id" :selected="item.id == prefecture">{{ item.name }}</option>
								</select>
							</div>
						</li>
						<li class="p-searchPanel__item">
							<div class="p-searchPanel__itemTitle"><i class="fa-solid fa-table-list p-searchPanel__icon"></i>業種</div>
							<div class="c-selectBox">
								<select v-model="industry" class="c-selectBox__select" name="industry">
									<option value="0">選択してください</option>
									<option v-for="item in industriesData" :key="item.id" :value="item.id" :selected="item.id == industry">{{ item.name }}</option>
								</select>
							</div>
						</li>
						<li class="p-searchPanel__item">
							<div class="p-searchPanel__itemTitle"><i class="fa-regular fa-user p-searchPanel__icon"></i>雇用形態</div>
							<div class="c-selectBox">
								<select v-model="employment_status" class="c-selectBox__select" name="employment_status">
									<option value="0">選択してください</option>
									<option v-for="item in employment_statusData" :key="item.id" :value="item.id" :selected="item.id == employment_status">{{ item.name }}</option>
								</select>
							</div>
						</li>
						<li class="p-searchPanel__item">
							<div class="p-searchPanel__itemTitle"><i class="fa-solid fa-yen-sign p-searchPanel__icon"></i>年収</div>
							<div class="c-selectBox p-searchPanel__annualIncome">
								<select v-model="annual_income" class="c-selectBox__select" name="annual_income">
									<option value="0">---</option>
									<option v-for="n in 17" :key="n" :value="n">{{ 150 + (n*50) }}</option>
								</select>
							</div>万円以上
						</li>
						<li class="p-searchPanel__item">
							<div class="p-searchPanel__itemTitle"><i class="fa-brands fa-pinterest p-searchPanel__icon"></i>こだわり条件</div>
							<div class="c-selectBox p-searchPanel__commitments">
								<button type="button" class="c-selectBtn" @click.prevent="open('typeCommitment')"><span>こだわり条件を選択</span></button>
								<!-- こだわり条件リスト -->
								<ul v-if="commitments.length > 0" class="c-selectList">
									<li v-for="commitment in commitments" class="c-selectList__item">
										<span class="c-selectList__label" 
											@click="deleteCheck('typeCommitment', commitment), 
											checkItemsCategory('typeCommitment', 0, commitment),
											changeActiveCheckArray('typeCommitment', commitment)">
											<i class="fa-solid fa-xmark c-selectList__icon"></i><span class="c-selectList__text">{{ commitment }}</span>
										</span>
									</li>
								</ul>
							</div>
						</li>
						<li class="p-searchPanel__item">
							<div class="p-searchPanel__itemTitle"><i class="fa-solid fa-pen p-searchPanel__icon"></i>キーワード</div>
							<div class="c-selectBox p-searchPanel__keyword">
								<input v-model="keyword" class="c-input" type="text" id="keyword" name="keyword" placeholder="会社名、職種名など">
								<div class="c-attention">※複数の場合、スペース区切り</div>
							</div>
						</li>
						<li class="p-searchPanel__item--btnArea">
							<div class="p-searchPanel__btnBase">
								<!-- 検索ボタン -->
								<i class="fa-solid fa-magnifying-glass p-searchPanel__btnIcon"></i>
								<input type="submit" @click.prevent="clickBtn(), changeSortNum(1), changePage(1)" class="c-btn p-searchPanel__btn" value="この条件で検索する">
							</div>
							<div class="p-searchPanel__btnBase u-mb0">
								<!-- リセットボタン(form内では「.prevent」がないと処理が進んでエラーになる) -->
								<button type="button" class="c-btn--delete p-searchPanel__btn--reset" @click.prevent="clickBtn(), resetQuery()">
									<i class="fa-solid fa-circle-xmark p-searchPanel__btnIcon--reset"></i><span class="u-tabHidden"></span>検索条件をリセット
								</button>
							</div>
						</li>
					</ul>
				</form>
			</div>
		</section>
	</div>
</template>

<script>
import Commitments from '../mixin/Commitments.vue';
import SearchJobMixin from '../mixin/SearchJobMixin.vue';
import SearchModal from '../components/SearchModal.vue';
import SearchModalMixin from '../mixin/SearchModalMixin.vue';

export default {
	props: [
	],

	components: {
		"SearchModal": SearchModal,
	},

	mixins: [ Commitments, SearchJobMixin, SearchModalMixin ],

	data(){
		return {
			// csrf対策
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		}
	},

	methods: {
		clickBtn(){
			this.$store.commit('Pagination/setIsClickBtn', true);
		},

		// 検索条件リセット
		resetQuery(){
			this.$store.commit('Pagination/setIndustry', 0);
			this.$store.commit('Pagination/setOccupation', 0);
			this.$store.commit('Pagination/setEmploymentStatus', 0);
			this.$store.commit('Pagination/setPrefecture', 0);
			this.$store.commit('Pagination/setAnnualIncome', 0);
			this.$store.commit('Pagination/setKeyword', null);
			this.$store.commit('Pagination/setSort', 1);
			this.$store.commit('Pagination/setCurrentPage', 1);
			this.$store.commit('Pagination/setCommitmentsAll', []);
			this.$store.commit('Pagination/setCategories', []);
			this.$store.commit('Pagination/setCategoryNum', 1);
			this.$store.commit('Pagination/setActiveCheckArray', []);
			this.$store.commit('Pagination/setIsActiveSearchPanel', false);
			this.changeUrl();
		},
	},

	computed:{
	}
}
</script>
