const state = {
	bottom: 20,
};

const mutations = {
	setBottom(state, num){
		state.bottom = num;
	},
}

export default {
  namespaced: true,
  state,
  mutations,
};