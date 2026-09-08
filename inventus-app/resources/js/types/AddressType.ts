import type Customer from "./Customer";
import type Supplier from "./Supplier";

// Type address
export default interface AddressType {
    id: number;
    zip_code: string;
    street: string;
    number: number;
    complement?: string;
    neighborhood: string;
    city: string;
    state: string;
    customer?: Customer;
    costumer?: Customer;
    supplier?: Supplier;
}