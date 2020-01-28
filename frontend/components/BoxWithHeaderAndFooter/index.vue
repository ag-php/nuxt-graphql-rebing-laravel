<template>
  <div class="board-column" :class="boxTheme">
    <div class="header">
      <span>FOOD CGS 34.16%</span>
    </div>
    <div class="content">
      <span>Target - $17,561</span>
    </div>
    <div class="footer">
      <span>$To Spend - 9, 288</span>
    </div>
  </div>
</template>

<script>

export default {
  name: 'BoxWithHeaderAndFooter',
  components: {
    
  },
  props: {
    boxTheme: {
      type: String,
      default: "blue"
    },
    header: {
      type: String,
      default: 'Header'
    },
    hasFooter: {
      type: Boolean,
      default: true
    },
    footer: {
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
  min-height: 160px;
  height: 160px;
  overflow: hidden;
  
  font-size: 12px;
  font-weight: 600;
  
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

  &.blue {
    background: #5acae8;
    color: black;
  }

  &.green {
    background: #6eba5c;
    color: white;
  }

  &.black {
    background: black;
    color: white;
  }

  .header {
    width: 100%;
    min-height: 25%;
    height: 25%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .content {
    width: 95%;
    min-height: 50%;
    height: 50%;
    background: white;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .footer {
    width: 100%;
    min-height: 25%;
    height: 25%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
}

</style>

