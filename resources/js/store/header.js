const state = {
	activeMenuNum: 1,
	isSearch: false,
};

const mutations = {
	// 下線をつけるタブを変更
	setActiveMenuNum(state, num){
		state.activeMenuNum = num;
	},
	setIsSearch(state, bool){
		state.isSearch = bool;
	},
}

export default {
  namespaced: true,
  state,
  mutations,
};