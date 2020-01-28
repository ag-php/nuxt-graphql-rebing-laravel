<template>
  <div class="board-column">
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
  name: 'BoxWithTitleBox',
  components: {
    
  },
  props: {
    titleText: {
      type: String,
      default: 'Header'
    },
    contentText: {
      type: String,
      default: 'Content'
    }
  },
  methods: {
    
  }
}
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
  .titie-box{
    width: 80%;
    min-height: 30%;
    height: 30%;
    border: 3px solid #6eba5c;
    color: black;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 12px;
    font-weight: 600;
  }
  .bridge-bar{
    width: 3px;
    min-height: 10%;
    height: 10%;
    background-image: linear-gradient(#6eba5c, black);
  }
  .content-box{
    width: 100%;
    min-height: 60%;
    height: 60%;
    border: 3px solid black;
    background: #5acae8;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
  }
}

</style>

