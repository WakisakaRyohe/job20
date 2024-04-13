const state = {
  form: {
    name: '',
    email: '',
    type: 0,
    subject: '',
    message: '',  
  },
	// テキストエリアの高さ
  message_height: "auto",
  // 送信済み判定フラグ
  isSubmit: false,
};

const mutations = {
  setName(state, str){
		state.form.name = str;
	},
	setEmail(state, str){
		state.form.email = str;
	},
  setType(state, str){
		state.form.type = str;
	},
  setSubject(state, str){
		state.form.subject = str;
	},
  setMessage(state, str){
		state.form.message = str;
	},
  // テキストエリアの高さ
	set_message_height(state, str){
		state.message_height = str;
	},
  // 送信判定フラグ
  setIsSubmit(state, bool){
		state.isSubmit = bool;
	},
};

export default {
  namespaced: true,
  state,
  mutations,
};