const state = {
	// フォームデータ
	form: {
		email_old: '',
		email_new: '',
		email_confirmation: '',	
	}
};

const mutations = {
	setEmailOld(state, str){
		state.form.email_old = str;
	},
	setEmailNew(state, str){
		state.form.email_new = str;
	},
	setEmailConfirmation(state, str){
		state.form.email_confirmation = str;
	},
};

export default {
  namespaced: true,
  state,
  mutations,
};