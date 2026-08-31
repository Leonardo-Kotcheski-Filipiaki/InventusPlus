import type { Person } from "./Person";
import type Address from "./AddressType";

export default interface Costumer {
    id: number;
    person?: Person;
    address?: Address;
}