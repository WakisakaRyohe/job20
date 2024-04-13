const state = {
  isLoading: true,
};

const mutations = {
	setIsLoading(state, bool){
		state.isLoading = bool;
	},
};

export default {
  namespaced: true,
  state,
  mutations,
};