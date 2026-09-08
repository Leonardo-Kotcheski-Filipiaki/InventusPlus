import type Customer from './Customer';
import type Stock from './Stock';
import type Supplier from './Supplier';

export default interface Sale {
    id: number;
    description: string | null;
    supplier_id: number | null;
    customer_id: number | null;
    stock_id: number;
    quantity: number;
    unit_value: number | string;
    sales_type: 'entrada' | 'saida' | string;
    created_at?: string;
    updated_at?: string;
    stock?: Stock;
    customer?: Customer;
    supplier?: Supplier;
}
