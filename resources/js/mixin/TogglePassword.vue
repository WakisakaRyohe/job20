<script>
export default {
	data(){
		return {
			togglePasswordOld: true,
			togglePassword: true,
			togglePasswordConfirmation: true,
		}
	},

	methods: {
		changeTogglePasswordOld: function() {
			this.togglePasswordOld = !this.togglePasswordOld;
		},
		changeTogglePassword: function() {
			this.togglePassword = !this.togglePassword;
		},
		changeTogglePasswordConfirmation: function() {
			this.togglePasswordConfirmation = !this.togglePasswordConfirmation;
		},
	},

	computed: {
		inputTypePasswordOld: function () {
			return this.togglePasswordOld ? "password" : "text" ;
		},
		inputTypePassword: function () {
			return this.togglePassword ? "password" : "text" ;
		},
		inputTypePasswordConfirmation: function () {
			return this.togglePasswordConfirmation ? "password" : "text" ;
		},
	},
}
</script>
