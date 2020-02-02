<template>
  <div>
    <h1>Ops Dashboard</h1>
    <br />
    <div class="top-container">
      <el-row type="flex" class="row-bg top-container" justify="center" style="padding-top: 20px;">
        <el-col :span="5" v-for="(topBox, i) in topBoxes" :key="i">
          <box-with-title-box :boxTheme="topBox.boxTheme">
            <template v-slot:header>
              <span>{{topBox.titleText}}</span>
            </template>
            <template v-slot:content>
              <div v-if="i==0">
                <el-date-picker
                  v-model="dateRange"
                  type="daterange"
                  align="right"
                  size="mini"
                  unlink-panels
                  start-placeholder="Start"
                  format="MM/dd/yyyy"
                  end-placeholder="End"
                  :clearable="false"
                  :picker-options="pickerOptions"
                ></el-date-picker>
              </div>
              <span v-if="i != 0">${{ topBox.contentText }}</span>
            </template>
          </box-with-title-box>
        </el-col>
      </el-row>
      <el-row
        type="flex"
        class="row-bg top-container"
        justify="center"
        style="padding-bottom: 20px;"
      >
        <el-col :span="3">
          <box-with-header-and-footer boxTheme="blue" :salesData="foodbev" title="FOOD"></box-with-header-and-footer>
        </el-col>
        <el-col :span="2">
          <bar-h barTheme="blue-black"></bar-h>
        </el-col>

        <el-col :span="3">
          <box-with-header-and-footer boxTheme="white" :salesData="beer" title="BEER"></box-with-header-and-footer>
        </el-col>
        <el-col :span="2">
          <bar-h barTheme="black-blue"></bar-h>
        </el-col>

        <el-col :span="3">
          <box-with-header-and-footer boxTheme="blue" :salesData="liquor" title="LIQUOR"></box-with-header-and-footer>
        </el-col>
        <el-col :span="2">
          <bar-h barTheme="blue-black"></bar-h>
        </el-col>

        <el-col :span="3">
          <box-with-header-and-footer boxTheme="white" :salesData="wine" title="WINE"></box-with-header-and-footer>
        </el-col>
        <el-col :span="2">
          <bar-h barTheme="black-green"></bar-h>
        </el-col>

        <el-col :span="3">
          <box-with-header-and-footer boxTheme="green"></box-with-header-and-footer>
        </el-col>
      </el-row>
    </div>

    <el-row type="flex" :gutter="20" class="row-bg" justify="center">
      <el-col :span="7">
        <box-with-border>
          <template v-slot:header>
            <span>SALES</span>
          </template>
          <template v-slot:content>
            <el-table :data="tableData" style="width: 100%">
              <el-table-column prop="category" label width="64"></el-table-column>
              <el-table-column prop="goal" label="GOAL"></el-table-column>
              <el-table-column prop="actual" label="ACTUAL"></el-table-column>
              <el-table-column prop="percentageOfGoal" label="% of GOAL"></el-table-column>
            </el-table>
          </template>
        </box-with-border>
      </el-col>

      <el-col :span="9">
        <box-with-border :hasBorderForContent="false">
          <template v-slot:header>
            <span>LABOR 32.87%</span>
          </template>
          <template v-slot:content>
            <el-row
              type="flex"
              class="row-bg"
              justify="center"
              style="width: 100%; margin-top: 16px;"
            >
              <el-col :span="11">
                <box-with-title-box boxTheme="green-black medium-content">
                  <template v-slot:header>
                    <span>MANAGEMENT</span>
                  </template>
                  <template v-slot:content>
                    <span>9.93%</span>
                  </template>
                </box-with-title-box>
              </el-col>
            </el-row>
            <el-row type="flex" class="row-bg" justify="center" style="width: 100%">
              <el-col :span="11">
                <box-with-title-box boxTheme="green-black medium-content">
                  <template v-slot:header>
                    <span>MANAGEMENT</span>
                  </template>
                  <template v-slot:content>
                    <span>9.93%</span>
                  </template>
                </box-with-title-box>
              </el-col>
              <el-col :span="11">
                <box-with-title-box boxTheme="green-black medium-content">
                  <template v-slot:header>
                    <span>MANAGEMENT</span>
                  </template>
                  <template v-slot:content>
                    <span>9.93%</span>
                  </template>
                </box-with-title-box>
              </el-col>
            </el-row>
          </template>
        </box-with-border>
      </el-col>

      <el-col :span="7">
        <box-with-border>
          <template v-slot:header>
            <span>DIRECT OF. EXAMPLES</span>
          </template>
          <template v-slot:content>
            <el-table :data="tableData" style="width: 100%">
              <el-table-column prop="category" label width="64"></el-table-column>
              <el-table-column prop="goal" label="GOAL"></el-table-column>
              <el-table-column prop="actual" label="ACTUAL"></el-table-column>
              <el-table-column prop="percentageOfGoal" label="% of GOAL"></el-table-column>
            </el-table>
          </template>
        </box-with-border>
      </el-col>
    </el-row>

    <el-row type="flex" class="row-bg" justify="center">
      <el-col :span="5" v-for="(bottomBox, i) in bottomBoxes" :key="i">
        <box-with-title-box :boxTheme="bottomBox.boxTheme">
          <template v-slot:header>
            <span>{{bottomBox.titleText}}</span>
          </template>
          <template v-slot:content>
            <span>{{bottomBox.contentText}}</span>
          </template>
        </box-with-title-box>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {
  convertCurrencySales,
  getCurrentWeekDays,
  getLastYearDays
} from "@/utils";
import BoxWithTitleBox from "@/components/BoxWithTitleBox";
import BoxWithHeaderAndFooter from "@/components/BoxWithHeaderAndFooter";
import BarH from "@/components/BarH";
import BoxWithBorder from "@/components/BoxWithBorder";

const amt = (d, i) => d.find(r => r.p === i).amount;

export default {
  middleware: "authenticated",
  layout: "app",
  components: {
    BoxWithTitleBox,
    BoxWithHeaderAndFooter,
    BarH,
    BoxWithBorder
  },
  data() {
    return {
      foodbev: {
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      },
      beer: {
        sales_actual: null,
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      },

      liquor: {
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      },
      wine: {
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      },
      apparel: {
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      },
      totalSales: null,
      lastYearSales: null,
      pickerOptions: {
        shortcuts: [
          {
            text: "Last week",
            onClick(picker) {
              let d = new Date();

              let day = d.getDay();
              let firstday = new Date(
                d.getFullYear(),
                d.getMonth(),
                d.getDate() + (day == 0 ? -6 : 1) - day - 7
              );
              let lastday = new Date(
                d.getFullYear(),
                d.getMonth(),
                d.getDate() + (day == 0 ? 0 : 7) - day - 7
              );

              picker.$emit("pick", [firstday, lastday]);
            }
          },
          {
            text: "Last month",
            onClick(picker) {
              let curr = new Date();
              let month = curr.getMonth();
              let year = curr.getFullYear();

              let firstDay = new Date(year, month - 1, 1);
              let lastDay = new Date(year, month, 0);

              picker.$emit("pick", [firstDay, lastDay]);
            }
          },
          {
            text: "Last 3 months",
            onClick(picker) {
              let curr = new Date();
              let month = curr.getMonth();
              let year = curr.getFullYear();

              let firstDay = new Date(year, month - 3, 1);
              let lastDay = new Date(year, month, 0);

              picker.$emit("pick", [firstDay, lastDay]);
            }
          }
        ]
      },
      dateRange: "",
      months: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
      ],
      topBoxes: [
        {
          id: 1,
          titleText: "DATE RANGE",
          contentText: "01/21/2019 - 01/21/2020",
          boxTheme: "green-blue"
        },
        {
          id: 2,
          titleText: "SALES",
          contentText: this.totalSales,
          boxTheme: "green-blue"
        },
        {
          id: 3,
          titleText: "LAST YEAR SALES",
          contentText: this.totalSales,
          boxTheme: "green-blue"
        }
      ],
      bottomBoxes: [
        {
          id: 1,
          titleText: "GROSS MARGIN",
          contentText: "41.7%",
          boxTheme: "green-dark-green large-content"
        },
        {
          id: 2,
          titleText: "IFO",
          contentText: "27.6%",
          boxTheme: "green-dark-green large-content"
        },
        {
          id: 3,
          titleText: "NET INCOME",
          contentText: "14.4%",
          boxTheme: "green-dark-green large-content"
        },
        {
          id: 4,
          titleText: "DISCOUNTS",
          contentText: "3.19%",
          boxTheme: "green-dark-green large-content"
        }
      ],
      tableData: [
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        },
        {
          category: "Food",
          goal: "217,163",
          actual: "140,000",
          percentageOfGoal: "63"
        }
      ],
      allSales: []
    };
  },
  computed: {},
  watch: {
    dateRange: async function(newValue) {
      let start = new Date(newValue[0]).toISOString().slice(0, 10);
      let end = new Date(newValue[1]).toISOString().slice(0, 10);
      this.allSales = await this.$store.dispatch("dashboardOps/getAllSales", {
        start: start,
        end: end
      });
    },

    allSales: {
      handler(val) {
        val.map(sales => {
          if (sales.item.startsWith("foodbev")) {
            this.foodbev[`${sales.item.slice(7)}_${sales.type}`] = sales.amount;
          } else if (sales.item.startsWith("beer")) {
            this.beer[`${sales.item.slice(4)}_${sales.type}`] = sales.amount;
          } else if (sales.item.startsWith("liquor")) {
            this.liquor[`${sales.item.slice(6)}_${sales.type}`] = sales.amount;
          } else if (sales.item.startsWith("wine")) {
            this.wine[`${sales.item.slice(4)}_${sales.type}`] = sales.amount;
          } else if (sales.item.startsWith("apparel")) {
            this.apparel[`${sales.item.slice(4)}_${sales.type}`] = sales.amount;
          }
        });

        this.totalSales =
          this.foodbev.sales_actual +
          this.beer.sales_actual +
          this.liquor.sales_actual +
          this.wine.sales_actual +
          this.apparel.sales_actual;
      },
      deep: true
    },

    totalSales: function(newValue) {
      console.log("totalsales changed", newValue);
      this.topBoxes[1].contentText = convertCurrencySales(newValue);
    },

    lastYearSales: function(newValue) {
      this.topBoxes[2].contentText = convertCurrencySales(newValue);
    }
  },
  methods: {},
  async mounted() {
    this.totalSales = "0";
    this.lastYearSales = "0";
    this.dateRange = getCurrentWeekDays();
    let lastYearDateRange = getLastYearDays();
    let lastYearSalesData = await this.$store.dispatch(
      "dashboardOps/getAllSales",
      {
        start: lastYearDateRange[0],
        end: lastYearDateRange[1]
      }
    );

    this.lastYearSales = lastYearSalesData.reduce((sum, sales) => {
        if (
          sales.type === "actual" &&
          [
            "beersales",
            "liquorsales",
            "foodbevsales",
            "winesales",
            "apparelsales"
          ].indexOf(sales.item) > -1
        ) {
          return sum + sales.amount;
        } else return sum;
      }, 0);
  }
};

// let SalesType = {
//   sales_actual: null,
//   sales_target: null,
//   purchase_actual: null,
//   purchase_target: null,
//   cgs_actual: null,
//   cgs_target: null
// }
</script>

<style scoped lang="scss">
.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
.el-col {
  border-radius: 4px;
}

.row-bg {
  padding: 10px 0;
}

.top-container {
  background: #0b263d;
}

.el-date-editor {
  width: 100%;
  color: white;
  &.el-input__inner {
    border: none !important;
    background: transparent !important;
    padding: 3px 12px !important;
  }
  /deep/ {
    .el-range__icon {
      color: white !important;
    }
    .el-range-separator {
      color: white !important;
    }
    .el-range-input {
      background: transparent !important;
      color: white !important;
      font-size: 14px !important;
      font-weight: 600 !important;
      -webkit-transform: scale(0.9, 1);
      -moz-transform: scale(0.9, 1);
      -ms-transform: scale(0.9, 1);
      -o-transform: scale(0.9, 1);
      transform: scale(0.9, 1);
      width: 100%;
      &::placeholder {
        color: white;
      }
    }
  }
}
</style>