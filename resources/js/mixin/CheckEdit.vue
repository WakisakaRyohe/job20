<script>
export default {
	data(){
		return {
			editFlg: false,
		}
	},
	computed: {
		btnText(){
			return (this.editFlg) ? '更新する' : '登録する' ;
		},
	},
}
</script>
