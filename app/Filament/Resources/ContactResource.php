<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('account_id')
                    ->required(),
                Forms\Components\TextInput::make('organization_id'),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(50),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(50),
                Forms\Components\TextInput::make('address')
                    ->maxLength(150),
                Forms\Components\TextInput::make('city')
                    ->maxLength(50),
                Forms\Components\TextInput::make('region')
                    ->maxLength(50),
                Forms\Components\TextInput::make('country')
                    ->maxLength(2),
                Forms\Components\TextInput::make('postal_code')
                    ->maxLength(25),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account_id'),
                Tables\Columns\TextColumn::make('organization_id'),
                Tables\Columns\TextColumn::make('first_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('address')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('region'),
                Tables\Columns\TextColumn::make('country'),
                Tables\Columns\TextColumn::make('postal_code')->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
            ])
            ->filters([
                    //Filter::make('deleted_at')
                    //->query(fn (Builder $query): Builder => $query->whereNull('deleted_at'))
                    SelectFilter::make('deleted_at')
                    ->options([
                        'with-trashed' => 'With Trashed',
                        'only-trashed' => 'Only Trashed',
                    ])->query(function (Builder $query, array $data){
                        $query->when($data['value'] === 'with-trashed', function(Builder $query){
                            $query->withTrashed();
                        })->when($data['value'] === 'only-trashed', function(Builder $query){
                            $query->onlyTrashed();
                    });
                })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }    
}
