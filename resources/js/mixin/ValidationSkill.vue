<script>
export default {
	// リアルタイムバリデーション
	watch: {
		// 保有資格(Computedwを登録している)
		// getCertifications(newValue, oldValue){
		// 	this.$store.commit('EditSkill/set_certifications', newValue);
		// 	console.log(newValue);
		// },

		// 保有資格
		certifications: {
			handler: function () {
				this.errors.certifications = [];

				// 保有資格が20個以上の場合
				if(this.certifications.length > 20){
					// console.log('a1:保有資格が20個以上の場合');
					this.errors.certifications.push('資格数を20個以下にしてください。');
				}
			},
			immediate: true,
		},

		// 英語の会話レベル
		speaking_english_level: {
			handler: function () {
				this.errors.speaking_english_level = [];
			},
			immediate: true,
		},

		// 英語の読解レベル
		reading_english_level: {
			handler: function () {
				this.errors.reading_english_level = [];
			},
			immediate: true,
		},

		// 英語の作文レベル
		writing_english_level: {
			handler: function () {
				this.errors.writing_english_level = [];
			},
			immediate: true,
		},
		
		// TOEICスコア
		toeic_score: {
			handler: function () {
				this.errors.toeic_score = [];

				// 正規表現パターンを定義
				const regex = /^[1-9]$|^[1-9][0-9]$|^[1-9][0-9][0-9]$/;

				// TOEICスコアがある場合
				if(this.toeic_score){
					// console.log('1:TOEICスコアがある場合');

					// TOEICスコアの形式が正しくない場合
					if(!regex.test(this.toeic_score)){
						this.errors.toeic_score.push('TOEICスコアは半角数字3桁以内で入力してください。');
						// console.log('2:TOEICスコアの形式が正しくない場合');
					}

				// TOEICスコアがない場合
				}else{
					// console.log('3:TOEICスコアがない場合');
				}
			},
			immediate: true,
		},

		// TOEFLスコア
		toefl_score: {
			handler: function () {
				this.errors.toefl_score = [];

				// 正規表現パターンを定義
				const regex = /^[1-9]$|^[1-9][0-9]$|^[1-9][0-9][0-9]$/;

				// TOEFLスコアがある場合
				if(this.toefl_score){
					// console.log('6:TOEFLスコアがある場合');

					// TOEFLスコアの形式が正しくない場合
					if(!regex.test(this.toefl_score)){
						this.errors.toefl_score.push('TOEFLスコアは半角数字3桁以内で入力してください。');
						// console.log('7:TOEFLスコアの形式が正しくない場合');
					}

				// TOEFLスコアがない場合
				}else{
					// console.log('8:TOEFLスコアがない場合');
				}
			},
			immediate: true,
		},

		// その他言語
		others_language: {
			handler: function () {
				this.errors.others_language = [];

				// その他言語が選択済みの場合
				if(this.others_language){
					this.errors.others_language = [];
					// console.log('a1:その他言語が選択済みの場合');

					// 読解・会話・作文レベル全て未入力の場合
					if(!this.reading_others_language_level && !this.speaking_others_language_level && !this.writing_others_language_level){
						this.errors.speaking_others_language_level = [];
						this.errors.reading_others_language_level = [];
						this.errors.writing_others_language_level = [];

						this.errors.speaking_others_language_level.push('その他言語の会話レベルを選択してください。');
						this.errors.reading_others_language_level.push('その他言語の読解レベルを選択してください。');
						this.errors.writing_others_language_level.push('その他言語の作文レベルを選択してください。');
						// console.log('a2:読解・会話・作文レベル全て未入力の場合');
					}

					// その他言語の会話レベル選択済みの場合
					if(this.speaking_others_language_level){
						this.errors.speaking_others_language_level = [];
						// console.log('a3:その他言語の会話レベル選択済みの場合');
					}

					// その他言語の読解レベル選択済みの場合
					if(this.reading_others_language_level){
						this.errors.reading_others_language_level = [];
						// console.log('a4:その他言語の読解レベル選択済みの場合');
					}

					// その他言語の作文レベル選択済みの場合
					if(this.writing_others_language_level){
						this.errors.writing_others_language_level = [];
						// console.log('a5:その他言語の作文レベル選択済みの場合');
					}

					// 「その他」が選択された場合、入力フォーム表示
					if(this.others_language === 'その他'){
						this.$store.commit('EditSkill/set_isSelectOthersLanguage', true);
						this.errors.input_others_language = [];
						this.errors.input_others_language.push('その他言語名を入力してください。');
						// console.log('a6:「その他」が選択された場合、入力フォーム表示');

					// 「その他」が選択された場合、入力したその他言語をリセット
					}else{
						this.$store.commit('EditSkill/set_isSelectOthersLanguage', false);
						this.$store.commit('EditSkill/set_input_others_language', '');
						this.errors.input_others_language = [];
						// console.log('a7:「その他」が選択された場合、入力したその他言語をリセット');
					}

				// その他言語が未選択の場合
				}else{
					this.errors.others_language = [];
					// console.log('a8:その他言語が未選択の場合');

					// 入力したその他言語をリセット
					this.$store.commit('EditSkill/set_isSelectOthersLanguage', false);
					this.$store.commit('EditSkill/set_input_others_language', '');
					this.errors.input_others_language = [];
					// console.log('a9:入力したその他言語をリセット');

					// いずれかのレベルがある場合
					if(this.speaking_others_language_level || this.reading_others_language_level || this.writing_others_language_level){
						this.errors.others_language.push('その他言語を選択してください。');
						// console.log('a10:いずれかのレベルがある場合');

					// 全てのレベルがない場合
					}else{
						this.errors.speaking_others_language_level = [];
						this.errors.reading_others_language_level = [];
						this.errors.writing_others_language_level = [];
						// console.log('a11:全てのレベルがない場合');
					}
				}
			},
			immediate: true,
		},

		// 入力したその他言語
		input_others_language: {
			handler: function () {
				this.errors.input_others_language = [];

				// 正規表現パターンを定義(ひらがな・カタカナ・漢字を含む)
				const regex = /^[ぁ-んァ-ヶー一-龯]+$/;

				// 入力したその他言語がある場合
				if(this.input_others_language){
					// console.log('b1;入力したその他言語がある場合');

					// 入力したその他言語が、ひらがな・カタカナ・漢字でない場合
					if(!regex.test(this.input_others_language)) {
						this.errors.input_others_language.push('その他言語名は、ひらがな・カタカナ・漢字で入力してください。');
						// console.log('b2;入力したその他言語が、ひらがな・カタカナ・漢字でない場合');

					// 入力したその他言語が20文字以上の場合
					}else if(this.input_others_language.length > 20){
						this.errors.input_others_language.push('その他言語は、20文字以下で入力してください。');
						// console.log('b3;入力したその他言語が20文字以上の場合');
					}

				// 入力したその他言語がない場合
				}else{
					this.errors.input_others_language = [];
					this.errors.input_others_language.push('その他言語名を入力してください。');
					// console.log('b4;入力したその他言語がない場合');

					if(this.others_language !== 'その他'){
						this.errors.input_others_language = [];
						// console.log('b5;入力したその他言語が正しく、再びその他言語を選択した場合');
					}
				}
			},
			immediate: true,
		},

		// その他言語の会話レベル
		speaking_others_language_level: {
			handler: function () {
				this.errors.speaking_others_language_level = [];

				// その他言語の会話レベルを選択した場合
				if(this.speaking_others_language_level){
					// console.log('c1:その他言語の会話レベルを選択した場合');

					// その他言語が選択済みの場合
					if(this.others_language){
						this.errors.reading_others_language_level = [];
						this.errors.writing_others_language_level = [];
						// console.log('c2:その他言語が選択済みの場合');

					// その他言語が未選択の場合
					}else{
						this.errors.others_language = [];
						this.errors.others_language.push('その他言語を選択してください。');
						// console.log('c3:その他言語が未選択の場合');
					}

				// その他言語の会話レベルを選択していない場合
				}else{
					// console.log('c4:その他言語の会話レベルが未選択の場合');

					// 言語が選択済みの場合
					if(this.others_language){
						// console.log('c5:言語が選択済みの場合');

						// 読解・作文レベルが未選択の場合
						if(!this.reading_others_language_level && !this.writing_others_language_level){
							this.errors.reading_others_language_level = [];
							this.errors.writing_others_language_level = [];
							this.errors.speaking_others_language_level.push('その他言語の会話レベルを選択してください。');
							this.errors.reading_others_language_level.push('その他言語の読解レベルを選択してください。');
							this.errors.writing_others_language_level.push('その他言語の作文レベルを選択してください。');
							// console.log('c6:読解・作文レベルが未選択の場合');
						}

					// 言語が未選択の場合
					}else{
						// console.log('c7:言語が未選択の場合');

						// 読解・作文レベルが未選択の場合
						if(!this.reading_others_language_level && !this.writing_others_language_level){
							this.errors.others_language = [];
							this.errors.speaking_others_language_level = [];
							this.errors.reading_others_language_level = [];
							this.errors.writing_others_language_level = [];
							// console.log('c8:読解・作文レベルが未選択の場合');
						}
					}
				}
			},
			immediate: true,
		},

		// その他言語の読解レベル
		reading_others_language_level: {
			handler: function () {
				this.errors.reading_others_language_level = [];

				// その他言語の読解レベルを選択した場合
				if(this.reading_others_language_level){
					// console.log('d1:その他言語の読解レベルを選択した場合');

					// その他言語が選択済みの場合
					if(this.others_language){
						this.errors.speaking_others_language_level = [];
						this.errors.reading_others_language_level = [];
						this.errors.writing_others_language_level = [];
						// console.log('d2:その他言語が選択済みの場合');

					// その他言語が未選択の場合
					}else{
						this.errors.others_language = [];
						this.errors.others_language.push('その他言語を選択してください。');
						// console.log('d3:その他言語が未選択の場合');
					}

				// その他言語の読解レベルを選択していない場合
				}else{
					// console.log('d4:その他言語の読解レベルが未選択の場合');

					// 言語が選択済みの場合
					if(this.others_language){
						// console.log('d5:言語が選択済みの場合');

						// 会話・作文レベルが未選択の場合
						if(!this.speaking_others_language_level && !this.writing_others_language_level){
							this.errors.speaking_others_language_level = [];
							this.errors.writing_others_language_level = [];
							this.errors.speaking_others_language_level.push('その他言語の会話レベルを選択してください。');
							this.errors.reading_others_language_level.push('その他言語の読解レベルを選択してください。');
							this.errors.writing_others_language_level.push('その他言語の作文レベルを選択してください。');
							// console.log('d6:会話・作文レベルが未選択の場合');
						}

					// 言語が未選択の場合
					}else{
						// console.log('d7:言語が未選択の場合');

						// 会話・作文レベルが未選択の場合
						if(!this.speaking_others_language_level && !this.writing_others_language_level){
							this.errors.others_language = [];
							this.errors.speaking_others_language_level = [];
							this.errors.reading_others_language_level = [];
							this.errors.writing_others_language_level = [];
							// console.log('d8:会話・作文レベルが未選択の場合');
						}
					}
				}
			},
			immediate: true,
		},

		// その他言語の作文レベル
		writing_others_language_level: {
			handler: function () {
				this.errors.writing_others_language_level = [];

				// その他言語の作文レベルを選択した場合
				if(this.writing_others_language_level){
					// console.log('e1:その他言語の作文レベルを選択した場合');

					// その他言語が選択済みの場合
					if(this.others_language){
						this.errors.others_language = [];
						this.errors.speaking_others_language_level = [];
						this.errors.reading_others_language_level = [];
						this.errors.writing_others_language_level = [];
						// console.log('e2:その他言語が選択済みの場合');

					// その他言語が未選択の場合
					}else{
						this.errors.others_language = [];
						this.errors.others_language.push('その他言語を選択してください。');
						// console.log('e3:その他言語が未選択の場合');
					}

				// その他言語の作文レベルを選択していない場合
				}else{
					// console.log('e4:その他言語の作文レベルが未選択の場合');

					// 言語が選択済みの場合
					if(this.others_language){
						// console.log('e5:言語が選択済みの場合');

						// 会話・読解レベルが未選択の場合
						if(!this.speaking_others_language_level && !this.reading_others_language_level){
							this.errors.speaking_others_language_level = [];
							this.errors.reading_others_language_level = [];
							this.errors.speaking_others_language_level.push('その他言語の会話レベルを選択してください。');
							this.errors.reading_others_language_level.push('その他言語の読解レベルを選択してください。');
							this.errors.writing_others_language_level.push('その他言語の作文レベルを選択してください。');
							// console.log('e6:会話・読解レベルが未選択の場合');
						}

					// 言語が未選択の場合
					}else{
						// console.log('e7:言語が未選択の場合');

						// 会話・読解レベルが未選択の場合
						if(!this.speaking_others_language_level && !this.reading_others_language_level){
							this.errors.others_language = [];
							this.errors.speaking_others_language_level = [];
							this.errors.reading_others_language_level = [];
							this.errors.writing_others_language_level = [];
							// console.log('e8:会話・読解レベルが未選択の場合');
						}
					}
				}
			},
			immediate: true,
		},

		// 資格・スキルオブジェクト（最後じゃないと上手く作動しない）
		skill: {
			handler: function () {
				// １つでも入力しないと更新ボタン押せない
				if( (this.certifications.length != 0 || 
					this.reading_english_level || this.speaking_english_level || this.writing_english_level || 
					this.toeic_score || this.toefl_score || this.others_language || 
					this.reading_others_language_level || this.speaking_others_language_level || this.writing_others_language_level ) && 
					!this.getErrorMessage(this.errors.certifications) && 
					!this.getErrorMessage(this.errors.toeic_score) && !this.getErrorMessage(this.errors.toefl_score) && 
					!this.getErrorMessage(this.errors.others_language) && 
					!this.getErrorMessage(this.errors.input_others_language) && 
					!this.getErrorMessage(this.errors.reading_others_language_level) && 
					!this.getErrorMessage(this.errors.speaking_others_language_level) && 
					!this.getErrorMessage(this.errors.writing_others_language_level) ) {
					
					this.isDisabled = false;
				}else{
					this.isDisabled = true;
				}
			},
			deep: true,
			immediate: true,
		},
	},
}
</script>
