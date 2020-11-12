<template>
  <div id="exampleInputEmoji">
    <div class="your-input-box">
      <textarea ref="whatsapp_message" class="form-control" v-model="valueInput"></textarea>
      <button class="btn" @click="toogleDialogEmoji"><i class="far fa-smile"></i></button>
    </div>
    <VEmojiPicker
      v-show="showDialog"
      labelSearch="Search"
      showSearch="false"
      lang="pt-BR"
      @select="onSelectEmoji"
      :i18n="i18n"
    />
  </div>
</template>

<script>
const i18n = {
  search: 'Pesquisar...',
  categories: {
    Activity: "Atividades",
    Flags: "Bandeiras",
    Foods: "Comida",
    Frequently: "Frequentes",
    Objects: "Objetos",
    Nature: "Natureza",
    Peoples: "Pessoas",
    Symbols: "Símbolos",
    Places: "Locais"
  }
}
import VEmojiPicker, { emojisDefault, categoriesDefault,  } from "v-emoji-picker";

export default {
  name: "exampleInputEmoji",
  components: {
    VEmojiPicker
  },
  data: () => ({
    valueInput: "Someting text ",
    showDialog: false,
    i18n
  }),
  mounted() {
    //console.log(categoriesDefault);
    //console.log('Total emojis:', emojisDefault.length)
  },
  methods: {
    toogleDialogEmoji() {
      this.showDialog = !this.showDialog;
    },
    onSelectEmoji(emoji) {
      this.valueInput += emoji.data;
      // Optional
      // this.toogleDialogEmoji();
    }
  }
};
</script>

<style lang="css" scoped>
/* THIS IS OPTIONAL */
/* @font-face {
  font-family: NotomojiColor;
  font-weight: 400;
  src: url(
      https://cdn.glitch.com/61908de1-dd0a-4359-a54b-6cb6d41bb5fd%2FNotoColorEmoji.ttf?1513108808150
    )format("truetype");
} */

#exampleInputEmoji {
  position: relative;
}

.your-input-box {
  display: flex;
  align-items: center;
  justify-content: center;
}

textarea {
  padding-left: 40px;
}

button {
    position: absolute;
    left: 0px;
    top: 0px;
    font-size: 1.5rem;
    padding: 0.5rem;
}
</style>
