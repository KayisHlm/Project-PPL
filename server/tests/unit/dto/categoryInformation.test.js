const CategoryInformation = require('../../../src/dto/category/categoryInformation');

describe('CategoryInformation DTO', () => {
  test('should map category with camelCase properties', () => {
    const category = {
      id: 1,
      name: 'Electronics',
      createdAt: '2024-01-01T00:00:00Z',
      updatedAt: '2024-01-02T00:00:00Z'
    };

    const dto = new CategoryInformation(category);

    expect(dto.id).toBe(1);
    expect(dto.name).toBe('Electronics');
    expect(dto.createdAt).toBe('2024-01-01T00:00:00Z');
    expect(dto.updatedAt).toBe('2024-01-02T00:00:00Z');
  });

  test('should map category with snake_case properties', () => {
    const category = {
      id: 2,
      name: 'Clothing',
      created_at: '2024-02-01T00:00:00Z',
      updated_at: '2024-02-02T00:00:00Z'
    };

    const dto = new CategoryInformation(category);

    expect(dto.id).toBe(2);
    expect(dto.name).toBe('Clothing');
    expect(dto.createdAt).toBe('2024-02-01T00:00:00Z');
    expect(dto.updatedAt).toBe('2024-02-02T00:00:00Z');
  });
});
