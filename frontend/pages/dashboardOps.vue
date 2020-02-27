<template>
  <div>
    <h1>Ops Dashboard</h1>
    <br />
    <div class="top-container">
      <el-row
        class="row-bg top-container"
        justify="center"
        style="padding-top: 20px;"
      >
        <el-col
          :sm="24"
          :md="8"
          v-for="(topBox, i) in topBoxes"
          :key="i"
          v-bind:class="{ 'mb-4': $device.isMobile }"
        >
          <box-with-title-box :boxTheme="topBox.boxTheme">
            <template v-slot:header>
              <span>{{ topBox.titleText }}</span>
            </template>
            <template v-slot:content>
              <div v-if="i == 0">
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
                  @change="dateRangeForTopBoxChanged"
                ></el-date-picker>
              </div>
              <span v-if="i != 0">${{ topBox.contentText }}</span>
            </template>
          </box-with-title-box>
        </el-col>
      </el-row>
      <el-row
        class="row-bg top-container"
        justify="center"
        style="padding-bottom: 20px;"
      >
        <el-col
          :sm="24"
          :md="11"
          :lg="3"
          v-bind:class="{ 'mb-4 mx-4': $device.isMobile }"
        >
          <box-with-header-and-footer
            boxTheme="blue"
            :salesData="foodbev"
            title="FOOD"
          ></box-with-header-and-footer>
        </el-col>
        <el-col v-if="!$device.isMobile" :sm="2" :md="2">
          <bar-h barTheme="blue-black"></bar-h>
        </el-col>

        <el-col
          :sm="24"
          :md="11"
          :lg="3"
          v-bind:class="{ 'mb-4 mx-4': $device.isMobile }"
        >
          <box-with-header-and-footer
            boxTheme="white"
            :salesData="beer"
            title="BEER"
          ></box-with-header-and-footer>
        </el-col>
        <el-col v-if="$device.isDesktop" :md="2">
          <bar-h barTheme="black-blue"></bar-h>
        </el-col>

        <el-col
          :sm="24"
          :md="11"
          :lg="3"
          v-bind:class="{ 'mb-4 mx-4': $device.isMobile }"
        >
          <box-with-header-and-footer
            boxTheme="blue"
            :salesData="liquor"
            title="LIQUOR"
          ></box-with-header-and-footer>
        </el-col>
        <el-col v-if="!$device.isMobile" :md="2">
          <bar-h barTheme="blue-black"></bar-h>
        </el-col>

        <el-col
          :sm="24"
          :md="11"
          :lg="3"
          v-bind:class="{ 'mb-4 mx-4': $device.isMobile }"
        >
          <box-with-header-and-footer
            boxTheme="white"
            :salesData="wine"
            title="WINE"
          ></box-with-header-and-footer>
        </el-col>
        <el-col v-if="$device.isDesktop" :md="2">
          <bar-h barTheme="black-green"></bar-h>
        </el-col>

        <el-col
          :sm="24"
          :md="11"
          :lg="3"
          v-bind:class="{ 'mb-4 mx-4': $device.isMobile }"
        >
          <box-with-header-and-footer
            boxTheme="green"
          ></box-with-header-and-footer>
        </el-col>
      </el-row>
    </div>

    <el-row :gutter="5" class="row-bg" justify="center">
      <el-col
        :sm="24"
        :md="12"
        :lg="10"
        v-bind:class="{ 'mb-4': $device.isMobile }"
      >
        <el-row :gutter="5" justify="center">
          <el-col :sm="24" :md="10" v-bind:class="{ 'mb-4': $device.isMobile }">
            <el-tree
              :data="locationEntries"
              node-key="id"
              :expand-on-click-node="false"
              @node-click="selectLocation"
              class="with-border"
            >
              <span class="custom-tree-node" slot-scope="{ node }">
                <span
                  v-bind:class="{
                    'location-label': node.label === location.label
                  }"
                  >{{ node.label.replace(/[^a-z0-9+]+/gi, " ") }}</span
                >
              </span>
            </el-tree>

            <el-date-picker
              v-model="month"
              type="month"
              placeholder="Pick a month"
              :picker-options="datePickerOptions"
              style="margin-top: 6px;"
              @change="periodChanged"
              class="with-border blue-box"
            ></el-date-picker>

            <el-select
              v-model="targetSelector"
              @change="targetSelectorChanged"
              style="margin-top: 6px;"
              class="with-border blue-box"
            >
              <el-option
                v-for="item in selectorOptions"
                :key="item"
                :label="item"
                :value="item"
              ></el-option>
            </el-select>
          </el-col>
          <el-col :sm="24" :md="14">
            <box-with-border>
              <template v-slot:header>
                <span>SALES</span>
              </template>
              <template v-slot:content>
                <el-table
                  :data="totalSalesTable"
                  style="width: 100%"
                  height="300"
                  max-height="300"
                >
                  <el-table-column
                    prop="category"
                    label
                    :min-width="20"
                  ></el-table-column>
                  <el-table-column
                    prop="actual"
                    label="ACTUAL"
                    :min-width="26.66"
                  >
                    <template slot-scope="scope">
                      <span>${{ convertCurrencySales(scope.row.actual) }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column prop="goal" label="GOAL" :min-width="26.66">
                    <template slot-scope="scope">
                      <span>{{
                        scope.row.goal >= 0
                          ? "$" + convertCurrencySales(scope.row.goal)
                          : "-$" +
                            convertCurrencySales(Math.abs(scope.row.goal))
                      }}</span>
                    </template>
                  </el-table-column>

                  <el-table-column
                    prop="percentageOfGoal"
                    label="% of GOAL"
                    :min-width="26.66"
                  >
                    <template slot-scope="scope">
                      <span>{{
                        convertCurrencySales(scope.row.percentageOfGoal)
                      }}</span>
                    </template>
                  </el-table-column>
                </el-table>
              </template>
            </box-with-border>
          </el-col>
        </el-row>
      </el-col>

      <el-col :sm="24" :md="12" :lg="8">
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
            <el-row
              type="flex"
              class="row-bg"
              justify="center"
              style="width: 100%"
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

      <el-col :sm="24" :md="12" :lg="6">
        <el-row type="flex" justify="center">
          <el-col :span="24">
            <box-with-border>
              <template v-slot:header>
                <span>DIRECT OF. EXAMPLES</span>
              </template>
              <template v-slot:content>
                <el-table :data="opexTable" style="width: 100%" height="300px">
                  <el-table-column
                    prop="category"
                    label
                    :min-width="34"
                  ></el-table-column>
                  <el-table-column prop="actual" label="Actual" :min-width="22">
                    <template slot-scope="scope">
                      <span>${{ convertCurrencySales(scope.row.actual) }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column prop="goal" label="GOAL" :min-width="22">
                    <template slot-scope="scope">
                      <span>{{
                        scope.row.goal >= 0
                          ? "$" + convertCurrencySales(scope.row.goal)
                          : "-$" +
                            convertCurrencySales(Math.abs(scope.row.goal))
                      }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    prop="percentageOfGoal"
                    label="% of GOAL"
                    :min-width="22"
                  >
                    <template slot-scope="scope">
                      <span>{{
                        convertCurrencySales(scope.row.percentageOfGoal)
                      }}</span>
                    </template>
                  </el-table-column>
                </el-table>
              </template>
            </box-with-border>
          </el-col>
        </el-row>
      </el-col>
    </el-row>

    <el-row class="row-bg" justify="center">
      <el-col
        :sm="24"
        :md="8"
        v-bind:class="{ 'mb-4': $device.isMobile }"
        v-for="(bottomBox, i) in bottomBoxes"
        :key="i"
      >
        <box-with-title-box :boxTheme="bottomBox.boxTheme">
          <template v-slot:header>
            <span>{{ bottomBox.titleText }}</span>
          </template>
          <template v-slot:content>
            <span>{{ bottomBox.contentText }}</span>
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
      locationEntries: [],
      location: {
        id: null,
        label: null,
        children: []
      },
      locations: [],
      currentPeriod: 1,
      periodNum: 1,
      period: null,
      month: null,
      datePickerOptions: {
        disabledDate(date) {
          return date > new Date() || date < new Date("01/01/2018");
        }
      },
      selectorOptions: ["Budget", "Q2 Forecast", "Q3 Forecast", "Q4 Forecast"],
      targetSelector: "Budget",
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
          contentText: this.lastYearSales,
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
      totalSalesTable: [
        {
          category: "Food",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Beer",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Liquor",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Wine",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Other",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        }
      ],
      opexTable: [
        {
          category: "Restaurant Supplies",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Restaurant Expenses",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Facility Expenses",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Administration",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Advertising",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Occupancy",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
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

      let startForLastYear = new Date(
        new Date(start).setFullYear(new Date(start).getFullYear() - 1)
      )
        .toISOString()
        .slice(0, 10);
      let endForLastYear = new Date(
        new Date(end).setFullYear(new Date(end).getFullYear() - 1)
      )
        .toISOString()
        .slice(0, 10);
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
      this.topBoxes[1].contentText = convertCurrencySales(newValue);
    },

    lastYearSales: function(newValue) {
      this.topBoxes[2].contentText = convertCurrencySales(newValue);
    }
  },
  methods: {
    convertToTree(list) {
      let map = {},
        node,
        roots = [],
        i;
      for (i = 0; i < list.length; i += 1) {
        map[list[i].id] = i;
        list[i].children = [];
      }
      for (i = 0; i < list.length; i += 1) {
        node = list[i];
        if (node.parent !== 0) {
          list[map[node.parent]].children.push(node);
        } else {
          roots.push(node);
        }
      }
      return roots;
    },
    
    async selectLocation(node) {
      this.location = node;
      await this.loadSalesForMiddleBoxes();
      await this.loadSalesForTopBoxes();
    },

    convertCurrencySales(value) {
      return convertCurrencySales(value);
    },

    async periodChanged() {
      this.periodNum = await this.$store.dispatch(
        "graphs/periodReverse",
        this.month
      );
      this.period = await this.$store.dispatch("graphs/period", this.periodNum);
      await this.loadSalesForMiddleBoxes();
    },

    async targetSelectorChanged() {
      await this.loadSalesForMiddleBoxes();
    },

    async dateRangeForTopBoxChanged() {
      await this.loadSalesForTopBoxes();
    },

    async loadSalesForTopBoxes() {
      this.initTopBoxes();
      return Promise.all([
        this.calculateAllSales(),
        this.calculatePriorYearSales()
      ]);
    },

    async loadSalesForMiddleBoxes() {
      this.initMiddleBoxTables();
      return Promise.all([this.loadSalesForMonth()]);
    },

    async loadSalesForMonth() {
      
      let sales = await this.$store.dispatch(
        "dashboardOps/getSalesForMiddleBoxes",
        {
          location: this.location.label,
          locationId: this.location.id,
          isParent: this.location.acct_type === "parent",
          period: this.periodNum,
          selector: this.targetSelector
        }
      );

      sales.forEach(sale => {
        if (sale.category === "totalsales") {
          if (sale.target.toLowerCase() === "actuals") {
            this.totalSalesTable[sale.rankInCategory - 2].actual += sale.amount;
          } else;
          this.totalSalesTable[sale.rankInCategory - 2].goal += sale.amount;
        } else if (sale.category === "opex") {
          if (sale.target.toLowerCase() === "actuals") {
            this.opexTable[sale.rankInCategory - 1].actual += sale.amount;
          } else this.opexTable[sale.rankInCategory - 1].goal += sale.amount;
        }
      });
      this.totalSalesTable = this.totalSalesTable.map(item => {
        return {
          ...item,
          percentageOfGoal:
            item.actual !== 0 && item.goal !== 0
              ? Number(item.actual / item.goal).toFixed(2) * 100
              : 0
        };
      });
      this.opexTable = this.opexTable.map(item => {
        return {
          ...item,
          percentageOfGoal:
            item.actual !== 0 && item.goal !== 0
              ? Number(item.actual / item.goal).toFixed(2) * 100
              : 0
        };
      });
    },

    async calculateAllSales() {
      let start = new Date(this.dateRange[0]).toISOString().slice(0, 10);
      let end = new Date(this.dateRange[1]).toISOString().slice(0, 10);

      let startForLastYear = new Date(
        new Date(start).setFullYear(new Date(start).getFullYear() - 1)
      )
        .toISOString()
        .slice(0, 10);
      let endForLastYear = new Date(
        new Date(end).setFullYear(new Date(end).getFullYear() - 1)
      )
        .toISOString()
        .slice(0, 10);

      this.allSales = await this.$store.dispatch("dashboardOps/getAllSales", {
        start: start,
        end: end,
        location: this.location.label,
        locationId: this.location.id,
        isParent: this.location.acct_type === "parent"
      });


    },

    async calculatePriorYearSales() {
      let start = new Date(this.dateRange[0]).toISOString().slice(0, 10);
      let end = new Date(this.dateRange[1]).toISOString().slice(0, 10);

      let startForLastYear = new Date(
        new Date(start).setFullYear(new Date(start).getFullYear() - 1)
      )
        .toISOString()
        .slice(0, 10);
      let endForLastYear = new Date(
        new Date(end).setFullYear(new Date(end).getFullYear() - 1)
      )
        .toISOString()
        .slice(0, 10);
      let lastYearSalesData = await this.$store.dispatch(
        "dashboardOps/getAllSales",
        {
          start: startForLastYear,
          end: endForLastYear,
          location: this.location.label,
          locationId: this.location.id,
          isParent: this.location.acct_type === "parent"
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
    },

    initTopBoxes() {
      this.totalSales = "0";
      this.lastYearSales = "0";
      this.foodbev = {
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      },
      this.beer = {
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      },

      this.liquor = {
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      },
      this.wine = {
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      },
      this.apparel = {
        sales_actual: null,
        sales_target: null,
        purchase_actual: null,
        purchase_target: null,
        cgs_actual: null,
        cgs_target: null
      }
    },

    initMiddleBoxTables() {
      this.totalSalesTable = [
        {
          category: "Food",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Beer",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Liquor",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Wine",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Other",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        }
      ];
      this.opexTable = [
        {
          category: "Restaurant Supplies",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Restaurant Expenses",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Facility Expenses",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Administration",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Advertising",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        },
        {
          category: "Occupancy",
          actual: 0,
          goal: 0,
          percentageOfGoal: 0
        }
      ];
    }
  },
  async mounted() {
    this.initTopBoxes();
    this.initMiddleBoxTables();

    this.dateRange = getCurrentWeekDays();
    let init = await this.$store.dispatch("graphs/periodReverse", new Date());
    this.currentPeriod = init;
    this.periodNum = init;

    this.period = await this.$store.dispatch("graphs/period", this.periodNum);
    this.month = new Date("01 " + this.period.month + " " + this.period.year);

    this.locations = await this.$store.dispatch("graphs/locations");

    this.locationEntries = JSON.parse(
      JSON.stringify(
        this.convertToTree(
          this.locations.map(item => ({
            ...item,
            id: item.acct_id,
            children: [],
            label: item.acct
          }))
        )
      )
    );

    this.location = this.locationEntries[0];

    await this.loadSalesForMiddleBoxes();

    await this.loadSalesForTopBoxes();
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
.mx-4 {
  padding-left: 16px;
  padding-right: 16px;
}
.mb-4 {
  margin-bottom: 16px;
}
.el-row {
  margin-bottom: 20px;
  display: flex;
  justify-content: center;
  flex-direction: row;
  @media (max-width: 768px) {
    flex-direction: column;
  }
  
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

.el-tree {
  &.with-border {
    border: 3px solid black;
  }

  /deep/ .el-tree-node {
    &.is-current {
      .el-tree-node__content {
        .custom-tree-node {
          .location-label {
            font-weight: 600;
          }
        }
      }
    }
  }
}

.el-select {
  width: 100%;
  color: white;
  &.with-border {
    border: 3px solid black;
    /deep/ .el-input__inner {
      border-radius: unset !important;
    }
  }
  &.blue-box {
    /deep/ {
      .el-input__inner {
        background: #5acae8 !important;
        color: white;
      }
      .el-input__prefix,
      .el-input__suffix {
        .el-input__icon {
          color: white !important;
        }
      }
    }
  }
}

.el-date-editor {
  width: 100%;
  color: white;
  &.with-border {
    border: 3px solid black;
    /deep/ .el-input__inner {
      border-radius: unset !important;
    }
  }
  &.blue-box {
    /deep/ {
      .el-input__inner {
        background: #5acae8 !important;
        color: white;
      }
      .el-input__prefix,
      .el-input__suffix {
        .el-input__icon {
          color: white !important;
        }
      }
    }
  }
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