<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('country_id')
                            ->label('Country')
                            ->options(Country::all()->pluck('name', 'id')->toArray())
                            ->reactive()->required()
                            ->afterStateUpdated(fn (callable $set) => $set('state_id', '')),

                        Select::make('state_id')
                            ->label('State')
                            ->options(function (callable $get) {
                                return State::whereCountryId($get('country_id'))->get()
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->reactive()->required()
                            ->afterStateUpdated(fn (callable $set) => $set('city_id', '')),

                        Select::make('city_id')
                            ->label('City')
                            ->options(function (callable $get) {
                                return City::whereStateId($get('state_id'))->get()
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->reactive()->required(),

                        Select::make('department_id')->relationship('department', 'name')->required(),
                        TextInput::make('firstname')->required()->maxLength(255),
                        TextInput::make('lastname')->required()->maxLength(255),
                        TextInput::make('address')->required()->maxLength(255),
                        TextInput::make('zip_code')->required()->maxLength(7),
                        DatePicker::make('birth_date')->required(),
                        DatePicker::make('date_hired')->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->rowIndex()->name('id'),
                TextColumn::make('firstname')->sortable()->searchable(),
                TextColumn::make('lastname')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(),
                TextColumn::make('address')->limit(5)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    }),
                TextColumn::make('zip_code'),
                TextColumn::make('birth_date')->date('jS F, Y'),
                TextColumn::make('date_hired')->date('jS F, Y'),
                TextColumn::make('created_at')->dateTime('jS F, Y h:ia')
            ])
            ->filters([
                SelectFilter::make('department')->relationship('department', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            EmployeeStatsOverview::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
