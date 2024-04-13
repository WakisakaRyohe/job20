const state = {
	newMessagesArray: [],
};

const mutations = {
	setNewMessagesArray(state, array){
		state.newMessagesArray = array;
	},
};

export default {
	namespaced: true,
	state,
	mutations,
};