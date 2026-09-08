import type StockCategory from "./StockCategory";
import type Supplier from "./Supplier";

export default interface Stock {
    id: number;
    name: string;
    description: string | null;
    quantity: number;
    unit_value: number | string;
    supplier_id: number;
    stock_category_id: number;
    stock_category?: StockCategory;
    supplier?: Supplier;
    created_at?: string;
    updated_at?: string;
}
