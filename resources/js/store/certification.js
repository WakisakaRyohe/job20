// 共有するデータ
const state = {
	// チェックしたアイテム
	items: [],
	// チェックしたアイテムが所属するカテゴリ
	categories: [],
	// 選択中のアイテムのカテゴリ
	categoryNum: 1,
	// インクリメンタルサーチのアイテムリスト
	suggestList: [],
	// チェックマークをつけるアイテムの配列
	activeCheckArray: [],
	// タブ切り替え
	isActiveTab: 1,
	// アコーディオン切り替え
	isShowTable: true,
	// フリーワード
	freeword: '',
	// フリーワード検索で見つからない場合の、入力画面表示フラグ
	isNoIndex: false,
	// フリーワード検索で見つからない場合の、入力したフリーワード
	inputFreeword: '', 
};

// ミューテーション(stateのデータ変更)
const mutations = {
	set_certifications(state, str){
		state.items = str;
	},
	set_certifications_all(state, array){
		state.items = array;
	},
 	setInputFreewordToArray(state, str){
		state.items.push(str);
	},
	setCategories(state, array){
		state.categories = array;
	},
  	setCategoryNum(state, num){
		state.categoryNum = num;
	},
  	setSuggestList(state, array){
		state.suggestList = array;
	},
	setFreeword(state, str){
		state.freeword = str;
	},
	setActiveTab(state, num){
		state.isActiveTab = num;
	},
	setNoIndex(state, bool){
		state.isNoIndex = bool;
	},
	setInputFreeword(state, str){
		state.inputFreeword = str;
	},
  	setActiveCheckName(state, str){
		state.activeCheckArray.push(str);
	},
  	setActiveCheckArray(state, array){
		state.activeCheckArray = array;
	},
	setIsShowTable(state, bool){
		state.isShowTable = bool;
	},
}

// ゲッター
const getters = {
};

// アクション
const actions = {
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};