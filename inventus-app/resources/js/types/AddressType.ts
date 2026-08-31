import type Costumer from "./Costumer";

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
    costumer?: Costumer;
}