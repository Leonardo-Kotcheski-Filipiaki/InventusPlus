export default interface StockCategory {
    id: number;
    name: string;
    description: string | null;
    stocks_count?: number;
    created_at?: string;
    updated_at?: string;
}
