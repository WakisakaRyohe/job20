<script>
export default {
	data(){
		return {
			alertMessage: '',
			sendAlertMessage: '入力データを送信していませんが、ページを移動しますか？',
			editAlertMessage: '入力データは登録完了していませんが、ページを移動しますか？',
		}
	},

	methods: {
		formChange(){
			this.$store.commit('FormChanged/set_formChanged', true);
			// console.log('formChange: ' + this.formChanged);
		},

		formSubmit(){
			this.$store.commit('FormChanged/set_formChanged', false);
			// console.log('formSubmit: ' + this.formChanged);
		},

		confirmSave (event) {
			if (this.formChanged){
				event.returnValue = this.alertMessage;
			}
		},
	},

	computed: {
		// VueXのデータ取得
		formChanged: function () {
			return this.$store.state.FormChanged.formChanged;
		},
	},

	mounted(){
		(this.alertType === 'send') ? this.alertMessage = this.sendAlertMessage : this.alertMessage = this.editAlertMessage ;
		window.addEventListener("beforeunload", this.confirmSave);
	},

	destroyed: function () {		
		window.removeEventListener("beforeunload", this.confirmSave);
	},

	beforeRouteLeave (to, from, next) {
		if (this.formChanged){
			const answer = window.confirm(this.alertMessage);

			if (answer) {
				this.$store.commit('FormChanged/set_formChanged', false);
				next();
			} else {
				next(false);
			}
		} else {
			next();
		}
	},
}
</script>
