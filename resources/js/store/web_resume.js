const state = {
	// タブ切り替え
	isActive: 1,
	isReverse: true,
	fromEditJobCareer: false,
};

const mutations = {
	set_isActive(state, num){
		state.isActive = num;
	},
	set_isReverse(state, bool){
		state.isReverse = bool;
	},
	set_fromEditJobCareer(state, bool){
		state.fromEditJobCareer = bool;
	},
};

const actions = {
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};