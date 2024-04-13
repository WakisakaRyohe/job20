const state = {
	formChanged: false,
};

const mutations = {
	set_formChanged(state, bool){
		state.formChanged = bool;
	},
};

export default {
	namespaced: true,
	state,
	mutations,
};