<script>
export default {
    methods: {
		// 画像のドラッグ＆ドロップ
		dragenter() {
			this.$store.dispatch('EditProfile/dragenter');
		},
		dragleave() {
			this.$store.dispatch('EditProfile/dragleave');
		},

		// ファイル選択時の処理
		onFileChange(e) {
			this.$store.dispatch('EditProfile/onFileChange', {e: e});
		},

		// 選択画像のリセット
		resetFile() {
			this.$store.commit('EditProfile/setImageData', '');
			this.$store.commit('EditProfile/setFile', '');

			const input = this.$refs.file;
			// インプット要素のtypeを一旦textへ変更して元に戻す
			input.type = 'text';
			input.type = 'file';
		},
    }
}
</script>

<style>
.dashedBorder{
	border: 3px #ccc dashed;
}
</style>