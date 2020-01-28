<template>
  <div class="board-column" :class="boxTheme">
    <div class="titie-box">
      <span>{{titleText}}</span>
    </div>
    <div class="bridge-bar"></div>
    <div class="content-box">
      <span>{{contentText}}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: "BoxWithTitleBox",
  components: {},
  props: {
    titleText: {
      type: String,
      default: "Header"
    },
    contentText: {
      type: String,
      default: "Content"
    },
    boxTheme: {
      type: String,
      default: "green-blue"
    }
  },
  methods: {}
};
</script>
<style lang="scss" scoped>
.board-column {
  min-height: 100px;
  height: 100px;
  overflow: hidden;
  margin: 0 15px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  .titie-box {
    width: 80%;
    min-height: 30%;
    height: 30%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 12px;
    font-weight: 600;
  }
  .bridge-bar {
    width: 3px;
    min-height: 10%;
    height: 10%;
  }
  .content-box {
    width: 100%;
    min-height: 60%;
    height: 60%;

    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
  }
  &.green-blue {
    .titie-box {
      border: 3px solid #6eba5c;
      color: black;
    }
    .bridge-bar {
      background-image: linear-gradient(#6eba5c, black);
    }
    .content-box {
      border: 3px solid black;
      background: #5acae8;
      color: white;
    }
  }
  &.green-black {
    .titie-box {
      border: 3px solid #6eba5c;
      color: black;
    }
    .bridge-bar {
      background-image: linear-gradient(#6eba5c, black);
    }
    .content-box {
      border: 3px solid black;
      background: black;
      color: white;
    }
  }
  &.green-dark-green {
    .titie-box {
      border: 3px solid #6eba5c;
      color: black;
    }
    .bridge-bar {
      background-image: linear-gradient(#6eba5c, #2b5f2e);
    }
    .content-box {
      border: 3px solid #2b5f2e;
      background: #6fba5d;
      color: white;
    }
  }
  &.large-content{
    .content-box {
      font-size: 24px;
    }
  }
  
}
</style>

