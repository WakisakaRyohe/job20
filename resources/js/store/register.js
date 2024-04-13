const state = {
	email: '',
	errors: {
		email: [],
	},
};

const mutations = {
	setEmail(state, str){
		state.email = str;
	},
	set_errors_email(state, str){
		state.errors.email.push(str);
	},
	// エラー配列リセット
	reset_errors_email(state, array){
		state.errors.email = array;
	},
}

export default {
  namespaced: true,
  state,
  mutations,
};