window.Vue = require('vue');
import VueRouter from 'vue-router';
import Account from '../components/AccountComponent.vue';
import Bank from '../components/BankComponent.vue';
import Bill from '../components/BillComponent.vue';
import BillPayment from '../components/BillPaymentComponent.vue';
import BranchOffice from '../components/BranchOfficeComponent.vue';
import Cashier from '../components/CashierComponent.vue';
import CreateBank from '../components/CreateBankComponent.vue';
import CreateBill from '../components/CreateBillComponent.vue';
import CreateBranchOffice from '../components/CreateBranchOfficeComponent.vue';
import CreateCashier from '../components/CreateCashierComponent.vue';
import CreateCapitulation from '../components/CreateCapitulationComponent.vue';
import CreateCollection from '../components/CreateCollectionComponent.vue';
import CreateContract from '../components/CreateContractComponent.vue';
import CreateCreditNote from '../components/CreateCreditNoteComponent.vue';
import CreateCustomer from '../components/CreateCustomerComponent.vue';
import CreateDeposit from '../components/CreateDepositComponent.vue';
import CreateDte from '../components/CreateDteComponent.vue';
import CreateElectronicDeposit from '../components/CreateElectronicDepositComponent.vue';
import CreateInduction from '../components/CreateInductionComponent.vue';
import CreateRequirement from '../components/CreateRequirementComponent.vue';
import CreateTax from '../components/CreateTaxComponent.vue';
import CreateTicket from '../components/CreateTicketComponent.vue';
import CreateTransaction from '../components/CreateTransactionComponent.vue';
import CreateTransbank from '../components/CreateTransbankComponent.vue';
import CreateManualSeat from '../components/CreateManualSeatComponent.vue';
import CreatePatent from '../components/CreatePatentComponent.vue';
import CreateVideotutorial from '../components/CreateVideotutorialComponent.vue';
import Capitulation from '../components/CapitulationComponent.vue';
import Collection from '../components/CollectionComponent.vue';
import CollectionAccounting from '../components/CollectionAccountingComponent.vue';
import Customer from '../components/CustomerComponent.vue';
import Contract from '../components/ContractComponent.vue';
import Dte from '../components/DteComponent.vue';
import Deposit from '../components/DepositComponent.vue';
import ElectronicCollection from '../components/ElectronicCollectionComponent.vue';
import ElectronicDeposit from '../components/ElectronicDepositComponent.vue';
import EditBank from '../components/EditBankComponent.vue';
import EditBill from '../components/EditBillComponent.vue';
import EditCollection from '../components/EditCollectionComponent.vue';
import EditDeposit from '../components/EditDepositComponent.vue';
import EditDte from '../components/EditDteComponent.vue';
import EditTicket from '../components/EditTicketComponent.vue';
import EndRequirement from '../components/EndRequirementComponent.vue';
import Induction from '../components/InductionComponent.vue';
import ImputeCapitulation from '../components/ImputeCapitulationComponent.vue';
import ImputeDte from '../components/ImputeDteComponent.vue';
import Requirement from '../components/RequirementComponent.vue';
import RefreshDte from '../components/RefreshDteComponent.vue';
import RefreshSeat from '../components/RefreshSeatComponent.vue';
import ReviewBillPayment from '../components/ReviewBillPaymentComponent.vue';
import ReviewCollection from '../components/ReviewCollectionComponent.vue';
import ReviewCapitulation from '../components/ReviewCapitulationComponent.vue';
import ReviewDeposit from '../components/ReviewDepositComponent.vue';
import ReviewTicket from '../components/ReviewTicketComponent.vue';
import ReviewDte from '../components/ReviewDteComponent.vue';
import ReviewRequirement from '../components/ReviewRequirementComponent.vue';
import ManualSeat from '../components/ManualSeatComponent.vue';
import SendDte from '../components/SendDteComponent.vue';
import Settlement from '../components/SettlementComponent.vue';
import Supplier from '../components/SupplierComponent.vue';
import ShowInduction from '../components/ShowInductionComponent.vue';
import ShowVideotutorial from '../components/ShowVideotutorialComponent.vue';
import Tax from '../components/TaxComponent.vue';
import Ticket from '../components/TicketComponent.vue';
import Turn from '../components/TurnComponent.vue';
import Transaction from '../components/TransactionComponent.vue';
import Transbank from '../components/TransbankComponent.vue';
import TransbankCollectionAccounting from '../components/TransbankCollectionAccountingComponent.vue';
import Patent from '../components/PatentComponent.vue';
import PaymentDte from '../components/PaymentDteComponent.vue';
import PayCapitulation from '../components/PayCapitulationComponent.vue';
import Videotutorial from '../components/VideotutorialComponent.vue';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
        { path: '/account', component: Account },
        { path: '/bank', component: Bank },
        { path: '/bank/create', component: CreateBank },
        { path: '/bank/edit/:id', component: EditBank },
        { path: '/bill', component: Bill },
        { path: '/bill/create', component: CreateBill },
        { path: '/bill/edit/:id', component: EditBill },
        { path: '/bill_payment', component: BillPayment },
        { path: '/bill_payment/review/:id', component: ReviewBillPayment },
        { path: '/branch_office', component: BranchOffice },
        { path: '/branch_office/create', component: CreateBranchOffice },
        { path: '/cashier', component: Cashier },
        { path: '/cashier/create', component: CreateCashier },
        { path: '/capitulation', component: Capitulation },
        { path: '/capitulation/create', component: CreateCapitulation },
        { path: '/capitulation/impute/:id', component: ImputeCapitulation },
        { path: '/capitulation/review/:id', component: ReviewCapitulation },
        { path: '/creditnote/create/:id', component: CreateCreditNote },
        { path: '/capitulation/pay/:rut', component: PayCapitulation },
        { path: '/collection', component: Collection },
        { path: '/collection/create', component: CreateCollection },
        { path: '/collection/edit/:id', component: EditCollection },
        { path: '/collection/review/:id', component: ReviewCollection },
        { path: '/collection_accounting', component: CollectionAccounting },
        { path: '/contract', component: Contract },
        { path: '/contract/create', component: CreateContract },
        { path: '/customer', component: Customer },
        { path: '/customer/create', component: CreateCustomer },
        { path: '/dte', component: Dte },
        { path: '/dte/create', component: CreateDte },
        { path: '/dte/refresh', component: RefreshDte },
        { path: '/dte/edit/:id', component: EditDte },
        { path: '/dte/impute/:id', component: ImputeDte },
        { path: '/dte/review/:id', component: ReviewDte },
        { path: '/dte/send/:id', component: SendDte },
        { path: '/dte/payment/:id', component: PaymentDte },
        { path: '/deposit', component: Deposit },
        { path: '/electronic_collection', component: ElectronicCollection },
        { path: '/electronic_deposit', component: ElectronicDeposit },
        { path: '/electronic_deposit/create', component: CreateElectronicDeposit },
        { path: '/deposit', component: Deposit },
        { path: '/deposit/edit/:id', component: EditDeposit },
        { path: '/deposit/review/:id', component: ReviewDeposit },
        { path: '/deposit/create', component: CreateDeposit },
        { path: '/deposit/create/:id', component: CreateElectronicDeposit },
        { path: '/induction', component: Induction },
        { path: '/induction/create', component: CreateInduction },
        { path: '/induction/show/:id', component: ShowInduction },
        { path: '/requirement', component: Requirement },
        { path: '/requirement/create', component: CreateRequirement },
        { path: '/requirement/end/:id', component: EndRequirement },
        { path: '/requirement/review/:id', component: ReviewRequirement },
        { path: '/settlement', component: Settlement },
        { path: '/supplier', component: Supplier },
        { path: '/ticket', component: Ticket },
        { path: '/ticket/create', component: CreateTicket },
        { path: '/ticket/review/:id', component: ReviewTicket },
        { path: '/ticket/edit/:id', component: EditTicket },
        { path: '/tax', component: Tax },
        { path: '/tax/create', component: CreateTax },
        { path: '/transaction', component: Transaction },
        { path: '/transaction/create', component: CreateTransaction },
        { path: '/transbank', component: Transbank },
        { path: '/transbank/create', component: CreateTransbank },
        { path: '/transbank_collection_accounting', component: TransbankCollectionAccounting },
        { path: '/turn', component: Turn },
        { path: '/manual_seat', component: ManualSeat },
        { path: '/manual_seat/create', component: CreateManualSeat },
        { path: '/seat/refresh', component: RefreshSeat },
        { path: '/patent', component: Patent },
        { path: '/patent/create', component: CreatePatent },
        { path: '/videotutorial', component: Videotutorial },
        { path: '/videotutorial/create', component: CreateVideotutorial },
        { path: '/videotutorial/show/:id', component: ShowVideotutorial },
    ],
});
