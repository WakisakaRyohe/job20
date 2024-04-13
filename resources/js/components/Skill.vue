<template>
	<div class="c-form--borderTopNone p-skill">

		<!-- 資格・スキルが登録済みの場合 -->
		<template v-if="skill">
			<h3 class="c-form__title--skill">資格・スキル</h3>

			<table class="c-table--showWebResume">
				<tbody>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">保有資格</span>
						</th>
						<td class="c-table__td">
							<ul v-if="skill.certifications.length > 0" class="p-skill__certificationsList">
								<li class="p-skill__certificationsItem" v-for="certification in skill.certifications" :key="certification">{{ certification }}</li>
							</ul>
							<template v-else>---</template>
						</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">英語資格・スキル</span>
						</th>
						<td class="c-table__td">
							<div class="p-skill__textUnit">
								<p>【実務レベル】</p>
								<dl class="p-skill__languageLevelArea">
									<dt class="p-skill__languageLevelType"><span class="c-badge--main">会話</span></dt>
									<dd class="p-skill__languageLevelValue"><span class=""><template v-if="skill.speaking_english_level">{{ skill.speaking_english_level }}</template><template v-else>---</template></span></dd>
								</dl>
								<dl class="p-skill__languageLevelArea">
									<dt class="p-skill__languageLevelType"><span class="c-badge--main">読解</span></dt>
									<dd class="p-skill__languageLevelValue"><span class=""><template v-if="skill.reading_english_level">{{ skill.reading_english_level }}</template><template v-else>---</template></span></dd>
								</dl>
								<dl class="p-skill__languageLevelArea">
									<dt class="p-skill__languageLevelType"><span class="c-badge--main">作文</span></dt>
									<dd class="p-skill__languageLevelValue"><span class=""><template v-if="skill.writing_english_level">{{ skill.writing_english_level }}</template><template v-else>---</template></span></dd>
								</dl>
							</div>
							<div class="p-skill__textUnit">
								<p>【保有資格】</p>
								<dl class="p-skill__languageLevelArea">
									<dt class="p-skill__languageLevelType--score"><span class="c-badge--sub">TOEIC</span></dt>
									<dd class="p-skill__languageLevelValue"><span class=""><template v-if="skill.toeic_score">{{ skill.toeic_score }}点</template><template v-else>---</template></span></dd>
								</dl>
								<dl class="p-skill__languageLevelArea">
									<dt class="p-skill__languageLevelType--score"><span class="c-badge--sub">TOEFL</span></dt>
									<dd class="p-skill__languageLevelValue"><span class=""><template v-if="skill.toefl_score">{{ skill.toefl_score }}点</template><template v-else>---</template></span></dd>
								</dl>
							</div>
						</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">その他の語学スキル</span>
						</th>
						<td class="c-table__td">
							<p class="u-mb20">【言語】
								<template v-if="skill.others_language">{{ skill.others_language }}</template><template v-else>---</template><template v-if="skill.input_others_language">（{{ skill.input_others_language }}）</template>
							</p>
							<div class="p-skill__textUnit">
								<p>【実務レベル】</p>
								<dl class="p-skill__languageLevelArea">
									<dt class="p-skill__languageLevelType"><span class="c-badge--main">会話</span></dt>
									<dd class="p-skill__languageLevelValue"><span class=""><template v-if="skill.speaking_others_language_level">{{ skill.speaking_others_language_level }}</template><template v-else>---</template></span></dd>
								</dl>
								<dl class="p-skill__languageLevelArea">
									<dt class="p-skill__languageLevelType"><span class="c-badge--main">読解</span></dt>
									<dd class="p-skill__languageLevelValue"><span class=""><template v-if="skill.reading_others_language_level">{{ skill.reading_others_language_level }}</template><template v-else>---</template></span></dd>
								</dl>
								<dl class="p-skill__languageLevelArea">
									<dt class="p-skill__languageLevelType"><span class="c-badge--main">作文</span></dt>
									<dd class="p-skill__languageLevelValue"><span class=""><template v-if="skill.writing_others_language_level">{{ skill.writing_others_language_level }}</template><template v-else>---</template></span></dd>
								</dl>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</template>

		<!-- 資格・スキル未登録の場合 -->
		<div v-else class="c-noData">
			資格・スキルは<span class="u-spShow"><br></span>登録されていません。
		</div>

		<!-- ボタンエリア -->
		<div class="c-form__alignCenterArea">
			<router-link class="c-btn--m" :to="{ name: 'edit_skill', params: { propSkill: skill } }">
				<template v-if="skill">更新</template><template v-else>登録</template>画面へ進む</router-link>
		</div>
	</div>		
</template>

<script>
export default {
	props: [
		'skill',
	],
	data(){
		return {
		}
	},
	methods:{
	},
	computed:{
	},
}
</script>