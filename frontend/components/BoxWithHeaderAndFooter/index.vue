<template>
  <div class="board-column" :class="boxTheme">
    <div class="header">
      <span>{{title}} CGS {{total}}%</span>
    </div>
    <div class="content">
      <div style="width: 100%;">
        <el-row>
          <el-col :span="14" style="text-align: right;">
            <p>Sales :</p>
          </el-col>
          <el-col :span="10" style="text-align: left;">
            <p>${{sales}}</p>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="14" style="text-align: right;">
            <p>CGS Target :</p>
          </el-col>
          <el-col :span="10" style="text-align: left;">
            <p>${{cgsTarget}}</p>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="14" style="text-align: right;">
            <p>Purchases :</p>
          </el-col>
          <el-col :span="10" style="text-align: left;">
            <p>${{purchase}}</p>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="14" style="text-align: right;">
            <p>To Spend :</p>
          </el-col>
          <el-col :span="10" style="text-align: left;">
            <p>{{Number(toSpend) >= 0 ? '$' + Number(toSpend) : '-$' + Math.abs(Number(toSpend))}}</p>
          </el-col>
        </el-row>
      </div>
    </div>
    <div class="footer">
      <span>Target - ${{target}}</span>
    </div>
  </div>
</template>

<script>
import { convertCurrencySales } from "@/utils";

export default {
  name: "BoxWithHeaderAndFooter",
  components: {},
  props: {
    boxTheme: {
      type: String,
      default: "blue"
    },
    salesData: {
      type: Object,
      default: function() {
        return {
          sales_actual: null,
          sales_target: null,
          purchase_actual: null,
          purchase_target: null,
          cgs_actual: null,
          cgs_target: null
        };
      }
    },
    totalSales: {
      type: Number,
      default: 0
    },
    title: {
      type: String,
      default: "Header"
    },
    hasFooter: {
      type: Boolean,
      default: true
    },
    footer: {
      type: String,
      default: "Content"
    }
  },
  watch: {
    salesData: {
      handler(val) {
        this.total = Math.round(
          this.$props.totalSales ? val.cgs_actual / this.$props.totalSales : 0
        );
        this.target = convertCurrencySales(val.cgs_target);
        this.sales = convertCurrencySales(val.sales_actual);
        this.cgsTarget = convertCurrencySales(this.target * this.sales);
        this.purchase = convertCurrencySales(val.purchase_actual);
        this.toSpend = convertCurrencySales(this.cgsTarget - this.purchase);
      },
      deep: true
    }
  },
  data() {
    return {
      total: 0,
      sales: 0,
      cgsTarget: 0,
      purchase: 0,
      toSpend: 0,
      target: 0
    };
  },
  methods: {
    convertCurrencySales(data) {
      return data ? Math.round(data).toLocaleString("en-GB") : 0;
    }
  }
};
</script>
<style lang="scss" scoped>
.board-column {
  min-height: 160px;
  height: 160px;
  overflow: hidden;

  font-size: 12px;
  font-weight: 600;
  // @media (max-width: 976px) {
  //   font-size: 10px;
  //   font-weight: 600;
  // }

  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

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
    background: white;
    padding: 10px 0px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 12px;
    font-weight: 600;
    @media (max-width: 1330px) {
      font-size: 0.85vw;
      font-weight: 600;
    }
    @media (max-width: 976px) {
      font-size: 10px;
      font-weight: 600;
    }
  }
  .footer {
    width: 100%;
    min-height: 25%;
    height: 25%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

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

  &.white {
    background: white;
    color: black;
    .content {
      background: #0b263d;
      color: white;
    }
  }
}
</style>

