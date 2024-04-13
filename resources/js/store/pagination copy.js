import router from '../router';

// 共有するデータ
const state = {
	baseUrl: '/job20/search', // ページネーションのパス作成用URL
	jobs: [], // 求人一覧
	pickupJobs: [], // カルーセルに表示する求人
	// 検索パラメータ
	industry: 0,
	occupation: 0,
	employment_status: 0,
	prefecture: 0,
	annual_income: 0,
	keyword: null,
	sort: 1,
	// こだわり条件
	commitments: [],
	categories: [], // チェックしたアイテムが所属するカテゴリ
	categoryNum: 1, // 選択中のアイテムのカテゴリ
	activeCheckArray: [], // チェックマークをつけるアイテムの配列
	isActiveTab: 2, // タブ切り替え
	isShowTable: true, // アコーディオン切り替え
	// ページネーション
	current_page: 1, // 現在のページ番号（初期値は1）
	job_range: 10, // １ページに表示する求人数
	page_range: 5, // １ページに表示するページネーション数
	last_page: null, // 総ページ数
	total: null, //ヒットしたレコードの数
	jobsNum: null, // 現在のページ用に取得した求人数
	// 検索パネルの表示切り替え
	isActiveSearchPanel: false,
	isShowSidebar: true,
	// 画面サイズ判定
	isWidthS: false,
	isWidthM: false,
	// カルーセル表示判定
	isShowCarousel: true,
	// 非同期通信のデータ取得判定
	isDataAcquired: false,
	// データ取得中の判定用の変数
	isLoading: true,
};

// ミューテーション(stateのデータ変更)
const mutations = {
	setJobs(state, data){
		state.jobs = data;
	},
	setPickupJobs(state, data){
		state.pickupJobs = data;
	},
	// 検索パラメータ
	setIndustry (state, num) {
		state.industry = num;
	},
	setOccupation (state, num) {
		state.occupation = num;
	},
	setEmploymentStatus (state, num) {
		state.employment_status = num;
	},
	setPrefecture (state, num) {
		state.prefecture = num;
	},
	setAnnualIncome (state, num) {
		state.annual_income = num;
	},
	setKeyword (state, str) {
		state.keyword = str;
	},
	setSort (state, num) {
		state.sort = num;
	},
	// こだわり条件
	setCommitments(state, str){
		state.commitments = str;
	},
	setCommitmentsAll(state, array){
		state.commitments = array;
	},
	setCategories(state, array){
		state.categories = array;
	},
  	setCategoryNum(state, num){
		state.categoryNum = num;
	},
  	setActiveCheckName(state, str){
		state.activeCheckArray.push(str);
	},
  	setActiveCheckArray(state, array){
		state.activeCheckArray = array;
	},
	setActiveTab(state, num){
		state.isActiveTab = num;
	},
	setIsShowTable(state, bool){
		state.isShowTable = bool;
	},
	// ページネーション
	setLastPage (state, page) {
		state.last_page = page;
	},
	setTotal (state, num) {
		state.total = num;
	},
	setJobsNum(state, num) {
		state.jobsNum = num;
	},
	// 現在のページ数変更
	setCurrentPage (state, page) {
		state.current_page = page;
	},
	incrementPage(state){
		state.current_page++;
	},
	decrementPage(state){
		state.current_page--;
	},
	// 検索パネルの表示切り替え
	setIsActiveSearchPanel (state, bool) {
		state.isActiveSearchPanel = bool;
	},	
	setIsShowSidebar (state, bool) {
		state.isShowSidebar = bool;
	},	
	// 画面サイズ判定
	setIsWidthS (state, bool) {
		state.isWidthS = bool;
	},
	setIsWidthM (state, bool) {
		state.isWidthM = bool;
	},
	// カルーセル表示判定
	setIsShowCarousel (state, bool) {
		state.isShowCarousel = bool;
	},
	// 非同期通信のデータ取得判定
	setIsDataAcquired (state, bool) {
		state.isDataAcquired = bool;
	},	
	// データ取得中
	setIsLoading (state, bool) {
		state.isLoading = bool;
	},	
}

// ゲッター
const getters = {};

// アクション
const actions = {
	//全レコード表示
	getJobs: function (context) {
		// console.log('getJobs');

		return axios.post('/job20/web/index', {
			//ページ番号を渡す
			current_page: context.state.current_page,
			job_range: context.state.job_range,
		}).then(response => {
			if(response.data.successFlg){
				context.commit('setJobs', response.data.jobs);
				context.commit('setPickupJobs', response.data.pickupJobs);
				context.commit('setLastPage', response.data.last_page);
				context.commit('setTotal', response.data.total);
				context.commit('setJobsNum', response.data.jobsNum);	
				context.commit('setIsDataAcquired', true);
			}else{
				// 同じパスに遷移する可能性があるので、コールバック関数でエラー回避
				router.push({ name: 'error'}, () => {});
			}
		}).catch(error => {
			console.log(error);
			router.push({ name: 'error'}, () => {});
		});
	},

	// 検索時のレコード表示
	searchJobs: function (context) {
		// console.log('searchJobs');

		return axios.post('/job20/web/search', {
			//検索パラメータと表示したいページ番号を渡す
			industry: context.state.industry,
			occupation: context.state.occupation,
			employment_status: context.state.employment_status,
			prefecture: context.state.prefecture,
			annual_income: context.state.annual_income,
			commitments: context.state.commitments,
			keyword: context.state.keyword,
			sort: context.state.sort,
			current_page: context.state.current_page,
			job_range: context.state.job_range,
		}).then(response => {
			if(response.data.successFlg){
				context.commit('setJobs', response.data.jobs);
				context.commit('setPickupJobs', response.data.pickupJobs);
				context.commit('setLastPage', response.data.last_page);
				context.commit('setTotal', response.data.total);
				context.commit('setJobsNum', response.data.jobsNum);
				context.commit('setIsDataAcquired', true);
			}else{
				// 同じパスに遷移する可能性があるので、コールバック関数でエラー回避
				router.push({ name: 'error'}, () => {});	
			}
		}).catch(error => {
			console.log(error);
			router.push({ name: 'error'}, () => {});
		});
	}
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};